<?php

use O\WordPress\Rest\Router;
use O\WordPress\Rest\ScopedRouter;

$router = new Router('wp-rest-router');
$router->route('scoped', function (ScopedRouter $router) {
    $router->delete('lol', function (WP_REST_Request $req, WP_REST_Response $res) {
        $res->set_data(['scoped-delete' => true]);
        return $res;
    });
});
$router->listen();
