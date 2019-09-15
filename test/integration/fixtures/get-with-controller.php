<?php

use Downshift\WordPress\Rest\Router;

class Controller {
    public function get(WP_REST_Request $req, WP_REST_Response $res)
    {
        $res->set_data(['get-with-controller' => true]);
        return $res;
    }
}

add_action('rest_api_init', function () {
    $router = new Router();
    $router->get('lol', 'Controller@get');
    $route = $router->routes()[0];
    register_rest_route('wp-rest-router', $route['path'], $route['args']);
});
