<?php

use Downshift\WordPress\Rest\Router;
use Downshift\WordPress\Rest\RestException;

$router = new Router('wp-rest-router');
$router->get('lol', function (WP_REST_Request $req, WP_REST_Response $res) {
    throw new RestException('error', [
        'foo' => true,
        'bar' => false,
    ]);
});
$router->listen();
