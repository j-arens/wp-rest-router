#!/bin/bash
set -euo pipefail

shopt -s expand_aliases
alias wp="/usr/local/bin/wp --allow-root --path=/var/www/html"

install_wordpress() {
  if [ ! -s /var/www/html/wp-config.php ]; then
    printf "downloading wordpress...\n"
    wp core download --version=latest --force

    printf "installing wordpress...\n"

    CATCH_ERRORS="define('WP_DISABLE_FATAL_ERROR_HANDLER', true);"
    DEBUG="define('WP_DEBUG', true);"
    FS_METHOD="define('FS_METHOD', 'direct');"
    
    wp config create \
      --dbname="${WP_DB_NAME}" \
      --dbuser="${WP_DB_USER}" \
      --dbpass="${WP_DB_PASS}" \
      --dbhost="${WP_DB_HOST}" \
      --dbprefix="${WP_DB_PREFIX:=wp_}" \
      --force \
      --extra-php="$(printf "$CATCH_ERRORS\n$DEBUG\n$FS_METHOD\n")"

    wp core install \
      --url="${WP_URL}" \
      --title="wp rest router integration tests" \
      --admin_user="${WP_ADMIN_USER}" \
      --admin_password="${WP_ADMIN_PASS}" \
      --admin_email="lol@lol.com" \
      --skip-email

    wp rewrite structure '/%postname%/'
    wp config set WP_HOME "${WP_URL}"
    wp config set WP_SITEURL "${WP_URL}"
    wp plugin activate wp-rest-router
  fi
}

write_htaccess() {
  local file="/var/www/html/.htaccess"
  if [ ! -e "$file" ]; then
    printf "writing .htaccess file...\n"
    cat > $file <<-EOF
# BEGIN WordPress
RewriteEngine On
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
# END WordPress
EOF
  fi
}

configure_apache() {
  printf "giving ownership of html dir to apache user...\n"
  chown -R www-data:www-data "/var/www/html"

  printf "enabling apache rewrite module...\n"
  a2enmod rewrite

  printf "starting apache in the foreground...\n"
  apachectl -D FOREGROUND
}

install_wordpress
write_htaccess
configure_apache
