<?php

use Downshift\WordPress\Rest\Router;

function get(WP_REST_Request $req, WP_REST_Response $res)
{
    $param = $req->get_param('param');
    $res->set_data(['param' => $param]);
    return $res;
}

add_action('rest_api_init', function () {
    $router = new Router();
    $router->get('lol', 'get')->setArg('param', ['required' => true, 'type' => 'integer']);
    $route = $router->routes()[0];
    register_rest_route('wp-rest-router', $route['path'], $route['args']);
});
