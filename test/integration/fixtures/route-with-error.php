<?php

use Downshift\WordPress\Rest\Router;
use Downshift\WordPress\Rest\RestException;

add_action('rest_api_init', function () {
    $router = new Router();
    $router->get('lol', function (WP_REST_Request $req, WP_REST_Response $res) {
        throw new RestException('error', [
            'foo' => true,
            'bar' => false,
        ]);
    });
    $route = $router->routes()[0];
    register_rest_route('wp-rest-router', $route['path'], $route['args']);
});
