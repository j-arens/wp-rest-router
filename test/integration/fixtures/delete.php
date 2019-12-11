<?php

use O\WordPress\Rest\Router;

$router = new Router('wp-rest-router');
$router->delete('lol', function (WP_REST_Request $req, WP_REST_Response $res) {
    $res->set_data(['delete' => true]);
    return $res;
});
$router->listen();
