<?php

use O\WordPress\Rest\Router;

$router = new Router('wp-rest-router');
$router->use(function (WP_REST_Request $req, WP_REST_Response $res, callable $next) {
    $res->header('X-Middleware', 'true');
});
$router->get('lol', function (WP_REST_Request $req, WP_REST_Response $res) {
    $res->set_data(['route-with-middleware' => true]);
    return $res;
});
$router->listen();
