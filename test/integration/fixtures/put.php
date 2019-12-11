<?php

use O\WordPress\Rest\Router;

$router = new Router('wp-rest-router');
$router->put('lol', function (WP_REST_Request $req, WP_REST_Response $res) {
    $res->set_data(['put' => true]);
    return $res;
});
$router->listen();
