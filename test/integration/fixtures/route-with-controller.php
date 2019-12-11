<?php

use O\WordPress\Rest\Router;

class Controller {
    public function endpoint(WP_REST_Request $req, WP_REST_Response $res)
    {
        $res->set_data(['route-with-controller' => true]);
        return $res;
    }
}

$router = new Router('wp-rest-router');
$router->get('lol', 'Controller@endpoint');
$router->listen();
