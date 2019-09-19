<?php

use Downshift\WordPress\Rest\Router;

function get(WP_REST_Request $req, WP_REST_Response $res)
{
    $res->set_data(['get-with-middleware' => true]);
    return $res;
}

$router = new Router('wp-rest-router');
$router->get('lol', 'get')->setPermission(function () {
    return current_user_can('edit_others_posts');
});
$router->listen();
