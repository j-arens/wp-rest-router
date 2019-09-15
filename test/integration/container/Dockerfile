ARG PHP_VERSION=7.3
FROM php:$PHP_VERSION-apache

COPY ./test/integration/container/entrypoint.sh /usr/local/downshift/entrypoint.sh

RUN docker-php-ext-install mysqli \
  && mv /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini \
  && curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
  && mv wp-cli.phar /usr/local/bin/wp \
  && chmod +x -R /usr/local/bin/wp /usr/local/downshift \
  && apt-get update \
  && apt-get install mariadb-client -y

EXPOSE 80

ENTRYPOINT ["/usr/local/downshift/entrypoint.sh"]
