<?php

use O\WordPress\Rest\Router;

function get(WP_REST_Request $req, WP_REST_Response $res)
{
    $param = $req->get_param('param');
    $res->set_data(['param' => $param]);
    return $res;
}

$router = new Router('wp-rest-router');
$router->get('lol', 'get')->setArg('param', ['required' => true, 'type' => 'integer']);
$router->listen();
