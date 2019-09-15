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

    /**
     * @return array
     */
    protected function routesToArray(): array
    {
        $resolver = $this->getResolver();
        return array_map(function (Route $route) use ($resolver) {
            if (is_callable($resolver)) {
                $route->setResolver($resolver);
            }
            return $route->toArray();
        }, $this->routes);
    }
}
