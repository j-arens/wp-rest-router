<?php

use Downshift\WordPress\Rest\Router;

add_action('rest_api_init', function () {
    $router = new Router();
    $router->post('lol', function (WP_REST_Request $req, WP_REST_Response $res) {
        $res->set_data(['post' => true]);
        return $res;
    });
    $route = $router->routes()[0];
    register_rest_route('wp-rest-router', $route['path'], $route['args']);
});
