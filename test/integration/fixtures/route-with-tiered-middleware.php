<?php

use Downshift\WordPress\Rest\Router;
use Downshift\WordPress\Rest\ScopedRouter;

add_action('rest_api_init', function () {
    $router = new Router();
    $router->use(function (WP_REST_Request $req, WP_REST_Response $res, callable $next) {
        $res->header('X-M1', 'true');
        $next();
    });
    $router->route('scoped', function (ScopedRouter $router) {
        $router->use(function (WP_REST_Request $req, WP_REST_Response $res, callable $next) {
            $headers = $res->get_headers();
            $res->header('X-M2', 'true');
            $next();
        });
        $router->get('lol', function (WP_REST_Request $req, WP_REST_Response $res) {
            $res->set_data(['route-with-tiered-middleware' => true]);
            return $res;
        });
    });
    $route = $router->routes()[0];
    register_rest_route('wp-rest-router', $route['path'], $route['args']);
});
