<?php declare(strict_types=1);

namespace Downshift\WordPress\Rest;

class Router extends AbstractRouter implements ScopeableRouterInterface
{
    /**
     * @var array
     */
    protected $scopes = [];

    /**
     * {@inheritdoc}
     */
    public function get(string $path, $endpoint): Route
    {
        $route = new Route(Router::GET, $path, $endpoint);
        $this->routes[] = $route;
        return $route;
    }

    /**
     * {@inheritdoc}
     */
    public function post(string $path, $endpoint): Route
    {
        $route = new Route(Router::POST, $path, $endpoint);
        $this->routes[] = $route;
        return $route;
    }

    /**
     * {@inheritdoc}
     */
    public function put(string $path, $endpoint): Route
    {
        $route = new Route(Router::PUT, $path, $endpoint);
        $this->routes[] = $route;
        return $route;
    }

    /**
     * {@inheritdoc}
     */
    public function patch(string $path, $endpoint): Route
    {
        $route = new Route(Router::PATCH, $path, $endpoint);
        $this->routes[] = $route;
        return $route;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(string $path, $endpoint): Route
    {
        $route = new Route(Router::DELETE, $path, $endpoint);
        $this->routes[] = $route;
        return $route;
    }

    /**
     * {@inheritdoc}
     */
    public function route(string $scope, ?callable $callback = null): RouterInterface
    {
        $router = new ScopedRouter($scope);
        $resolver = $this->getResolver();
        if (is_callable($resolver)) {
            $router->setResolver($resolver);
        }
        $this->scopes[] = $router;
        if (is_callable($callback)) {
            $callback($router);
        }
        return $router;
    }

    /**
     * @return array
     */
    public function routes(): array
    {
        return array_map(function (array $route) {
            $cb = $route['args']['callback'];
            if (isset($this->middlewares) && count($this->middlewares)) {
                $stack = new MiddlewareStack($this->middlewares);
                $route['args']['callback'] = $this->tryCatch(
                    $this->provideResponse(
                        $stack->apply($cb)
                    )
                );
            } else {
                $route['args']['callback'] = $this->tryCatch(
                    $this->provideResponse($cb)
                );
            }
            return $route;
        }, $this->routesToArray());
    }

    /**
     * @return array
     */
    protected function routesToArray(): array
    {
        $resolver = $this->getResolver();
        $routes = array_map(function (Route $route) use ($resolver) {
            if (is_callable($resolver)) {
                $route->setResolver($resolver);
            }
            return $route->toArray();
        }, $this->routes);

        return array_merge($routes, $this->scopedRoutesToArray());
    }

    /**
     * @return array
     */
    protected function scopedRoutesToArray(): array
    {
        return array_reduce($this->scopes, function (array $acc, ScopedRouter $scoped) {
            return array_merge($acc, $scoped->routes());
        }, []);
    }
}
