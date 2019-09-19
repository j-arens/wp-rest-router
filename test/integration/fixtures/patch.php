<?php

use Downshift\WordPress\Rest\Router;

$router = new Router('wp-rest-router');
$router->patch('lol', function (WP_REST_Request $req, WP_REST_Response $res) {
    $res->set_data(['patch' => true]);
    return $res;
});
$router->listen();
