<?php

use Downshift\WordPress\Rest\Router;
use Downshift\WordPress\Rest\ScopedRouter;

$router = new Router('wp-rest-router');
$router->route('scoped', function (ScopedRouter $router) {
    $router->put('lol', function (WP_REST_Request $req, WP_REST_Response $res) {
        $res->set_data(['scoped-put' => true]);
        return $res;
    });
});
$router->listen();
