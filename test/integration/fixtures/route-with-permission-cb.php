<?php

use Downshift\WordPress\Rest\Router;

function get(WP_REST_Request $req, WP_REST_Response $res)
{
    $res->set_data(['get-with-middleware' => true]);
    return $res;
}

add_action('rest_api_init', function () {
    $router = new Router();
    $router->get('lol', 'get')->setPermission('is_user_logged_in');
    $route = $router->routes()[0];
    register_rest_route('wp-rest-router', $route['path'], $route['args']);
});
