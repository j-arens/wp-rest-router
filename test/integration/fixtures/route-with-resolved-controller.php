<?php

use Downshift\WordPress\Rest\Router;

class Foo
{
    protected $bar;

    public function __construct(Bar $bar)
    {
        $this->bar = $bar;
    }

    public function get(WP_REST_Request $req, WP_REST_Response $res)
    {
        return $this->bar->get($req, $res);
    }
}

class Bar {
    public function get(WP_REST_Request $req, WP_REST_Response $res)
    {
        $res->set_data(['route-with-resolved-controller' => true]);
        return $res;
    }
}

add_action('rest_api_init', function () {
    $router = new Router();
    $router->setResolver(function (string $classname) {
        return new $classname(new Bar());
    });
    $router->get('lol', 'Foo@get');
    $route = $router->routes()[0];
    register_rest_route('wp-rest-router', $route['path'], $route['args']);
});
