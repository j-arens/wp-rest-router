<?php

use Downshift\WordPress\Rest\Router;

add_action('rest_api_init', function () {
    $router = new Router();
    $router->use(function (WP_REST_Request $req, WP_REST_Response $res, callable $next) {
        $res->header('X-Middleware', 'true');
        $next();
    });
    $router->get('lol', function (WP_REST_Request $req, WP_REST_Response $res) {
        $res->set_data(['get-with-middleware' => true]);
        return $res;
    });
    $route = $router->routes()[0];
    register_rest_route('wp-rest-router', $route['path'], $route['args']);
});
