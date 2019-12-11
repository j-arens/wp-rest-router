<?php

use O\WordPress\Rest\Router;

$router = new Router('wp-rest-router');
$router->post('lol', function (WP_REST_Request $req, WP_REST_Response $res) {
    $res->set_data(['post' => true]);
    return $res;
});
$router->listen();
