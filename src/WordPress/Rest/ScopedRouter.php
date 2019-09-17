<?php declare(strict_types=1);

namespace Downshift\WordPress\Rest;

class ScopedRouter extends AbstractRouter
{
    /**
     * @var string
     */
    protected $scope;
    
    /**
     * @param string $scope
     */
    public function __construct(string $scope)
    {
        $this->scope = $scope;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $path, $endpoint): Route
    {
        $route = new Route(ScopedRouter::GET, $this->getPath($path), $endpoint);
        $this->routes[] = $route;
        return $route;
    }

    /**
     * {@inheritdoc}
     */
    public function post(string $path, $endpoint): Route
    {
        $route = new Route(ScopedRouter::POST, $this->getPath($path), $endpoint);
        $this->routes[] = $route;
        return $route;
    }

    /**
     * {@inheritdoc}
     */
    public function put(string $path, $endpoint): Route
    {
        $route = new Route(ScopedRouter::PUT, $this->getPath($path), $endpoint);
        $this->routes[] = $route;
        return $route;
    }

    /**
     * {@inheritdoc}
     */
    public function patch(string $path, $endpoint): Route
    {
        $route = new Route(ScopedRouter::PATCH, $this->getPath($path), $endpoint);
        $this->routes[] = $route;
        return $route;
    }
    
    /**
     * {@inheritdoc}
     */
    public function delete(string $path, $endpoint): Route
    {
        $route = new Route(ScopedRouter::DELETE, $this->getPath($path), $endpoint);
        $this->routes[] = $route;
        return $route;
    }

    /**
     * @return array
     */
    public function routes(): array
    {
        $resolver = $this->getResolver();

        $routes = array_map(function (Route $route) use ($resolver) {
            if (is_callable($resolver)) {
                $route->setResolver($resolver);
            }
            return $route->toArray();
        }, $this->routes);

        // apply middleware that belongs to this scope only
        return array_map(function (array $route) {
            $cb = $route['args']['callback'];
            if (isset($this->middlewares) && count($this->middlewares)) {
                $stack = new MiddlewareStack($this->middlewares);
                $route['args']['callback'] = $stack->apply($cb);
            }
            return $route;
        }, $routes);
    }

    /**
     * @param string $path
     * @return string
     */
    protected function getPath(string $path): string
    {
        if (!$path) {
            return $this->scope;
        }
        return "{$this->scope}/$path";
    }
}
