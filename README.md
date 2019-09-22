WP Rest Router ![](https://github.com/j-arens/wp-rest-router/workflows/CI/badge.svg)
========

WP Rest Router is an abstraction around registering custom REST routes in WordPress. It's goal is to simplify and enhance the native WordPress API by providing a similar developer experience from frameworks like [Laravel](https://laravel.com/) and [Express](https://expressjs.com/).

Creating routes is achieved in the same manner as creating routes in most popular frameworks.

```php
use Downshift\WordPress\Rest\Router;

// create a new router, you'll need to provide it a namespace to use when registering routes with WordPress
// @see https://developer.wordpress.org/reference/functions/register_rest_route/#parameters
$router = new Router('my-namespace');

// unlike regular WordPress endpoint callbacks, you can always expect
// to receive a request AND response object in your route callback
$router->get('my-route', function (WP_REST_Request $req, WP_REST_Response $res) {
  // endpoint logic here
  return $res;
});

// POST
$router->post('my-route', ...);

// PUT
$router->put('my-route', ...);

// PATCH
$router->patch('my-route', ...);

// DELETE
$router->delete('my-route', ...);

// registers routes with WordPress
// listen attempts to register routes at the right time
// so you can skip hooking into the rest_api_init action if you’d like
// listen will throw an exception if its detected that its being called too late in the request
$router->listen();
```

Routes can be scoped or grouped to a specific path by using the `route` method.

```php
use Downshift\WordPress\Rest\Router;

$router = new Router('my-namespace');

// route will accept a callback as its second argument
$router->route('foo', function ($scope) {
  // wp-json/my-namespace/foo/bar
  $scope->get('bar', ...);
  // wp-json/my-namespace/foo/baz
  $scope->get('baz', ...);
});

// you may also choose to omit providing a callback and simply use the returned scoped router
$fooScope = $router->route('foo');
$fooScope->get('bar', ...);

// route may also be used to easily define multiple methods for a single route
$router->route('foo', function ($scope) {
  // GET wp-json/my-namespace/foo
  $scope->get('', ...);
  // POST wp-json/my-namespace/foo
  $scope->post('', ...)
});
```

Similar to how Laravel uses controller classes, routes can be created with a string that's made up of a class name and method name separated by the @ character passed as the callback. The router will take care of instantiating the class and invoking the given method under the hood.

```php
use Downshift\WordPress\Rest\Router;

class MyController
{
  /**
   * Lists some items
   *
   * @param WP_REST_Request $req
   * @param WP_REST_Response $res
   * @return WP_REST_Response
   */
  public function list(WP_REST_Request $req, WP_REST_Response $res): WP_REST_Response
  {
    // endpoint logic here
    return $res;
  }
}

$router = new Router('my-namespace');

// the router will take care of instantiating MyController and invoking the list method under the hood
$router->get('my-route', 'MyController@list');
// or if you're using namespaces
$router->get('my-route', 'MyNamespace\Controllers\MyController@list');
```

Take note that the router doesn't know how to resolve constructor parameters on controller classes. If you find yourself in this situation or are using a dependency injection container then you'll need to provide the router with a resolver function.

```php
use Downshift\WordPress\Rest\Router;

class SomeRepository
{
  /**
   * Queries the database
   *
   * @param string $id
   * @return array
   */
  public function find(string $id): array
}

class MyController
{

  /**
   * @var SomeRepository
   */
  protected $repo;

  /**
   * @param SomeRepository $repo
   */
  public function __construct(SomeRepository $repo)
  {
    $this->repo = $repo;
  }

  /**
   * Lists some items
   *
   * @param WP_REST_Request $req
   * @param WP_REST_Response $res
   * @return WP_REST_Response
   */
  public function list(WP_REST_Request $req, WP_REST_Response $res): WP_REST_Response
  {
    $id = $req->get_param('id');
    $items = $this->repo->find($id);
    $res->set_data(json_encode($items));
    return $res;
  }
}

$router = new Router('my-namespace');

// provide the router with a function that resolves classes through a DI container
$router->setResolver(function (string $classname) {
  $container = SomeDIContainer::getInstance();
  return $container->create($classname);
});

// when the router needs to create a new instance of MyController it will first check
// if a resolver function has been provided and invoke it with the name of the class
// its trying to create, your resolver function should return a new instance of that class
$router->get('my-route', 'MyController@list');
```

One of the cooler parts of WP Rest Router is that it makes it easy to define and use Express-like middleware functions. Take note that unlike Express, route callbacks will always be called last after all middleware functions.

```php
use Downshift\WordPress\Rest\Router;

$router = new Router('my-namespace');

// similar to route callbacks, middleware functions will always be provided with
// a request and response object, the only difference here is that middleware functions
// are also provided with a next function as their last argument - just like Express middlewares
// calling $next() at the end of your middleware allows the chain to continue
function fooMiddleware(WP_REST_Request $req, WP_REST_Response $res, callable $next)
{
  $res->header('x-foo', true);
  $next();
}

// you may however choose to conditionally not call $next
// this will short circuit the chain and prompt the router to return the current
// response object as-is, skipping any remaining middleware and the route callback
function barMiddleware(WP_REST_Request $req, WP_REST_Response $res, callable $next)
{
  // continue the chain if my-param is truthy
  if ($req->get_param('my-param')) {
    $next();
  }
  // otherwise stop the chain and return the response as-is
}

// any callable can be added as a middleware with the use method
// middleware functions are called in the order that they are added
$router->use('fooMiddleware');
$router->use('barMiddleware');
```

Middleware may also be applied to scoped routes.

```php
use Downshift\WordPress\Rest\Router;

$router = new Router('my-namespace');

function fooMiddleware(WP_REST_Request $req, WP_REST_Response $res, callable $next)
{
  $res->header('x-foo', true);
  $next();
}

function barMiddleware(WP_REST_Request $req, WP_REST_Response $res, callable $next)
{
  $res->header('x-bar', true);
  $next();
}

// apply the foo middleware to all incoming requests
$router->use('fooMiddleware');

$router->route('scoped-route', function ($scope) {
  // apply the barMiddleware to all requests within this scope only
  // middleware applied to all requests will run first
  $scope->use('barMiddleware');
});
```

# Development

### Requirements

* composer
* docker
* docker-compose
* php 7.1
