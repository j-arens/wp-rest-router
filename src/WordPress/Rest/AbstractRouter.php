<?php declare(strict_types=1);

namespace Downshift\WordPress\Rest;

abstract class AbstractRouter implements RouterInterface
{
    use CallbackWrappers;
    use UsesResolver;

    /**
     * @var string
     */
    const GET = 'GET';

    /**
     * @var string
     */
    const POST = 'POST';

    /**
     * @var string
     */
    const PUT = 'PUT';

    /**
     * @var string
     */
    const PATCH = 'PATCH';

    /**
     * @var string
     */
    const DELETE = 'DELETE';

    /**
     * @var array
     */
    protected $routes = [];

    /**
     * @var array
     */
    protected $middlewares = [];

    /**
     * @var callable
     */
    protected $resolver;

    /**
     * {@inheritdoc}
     */
    abstract public function get(string $route, $endpoint): Route;

    /**
     * {@inheritdoc}
     */
    abstract public function post(string $route, $endpoint): Route;

    /**
     * {@inheritdoc}
     */
    abstract public function put(string $route, $endpoint): Route;

    /**
     * {@inheritdoc}
     */
    abstract public function patch(string $route, $endpoint): Route;

    /**
     * {@inheritdoc}
     */
    abstract public function delete(string $route, $endpoint): Route;

    /**
     * {@inheritdoc}
     */
    public function use(callable $middleware)
    {
        $this->middlewares[] = $middleware;
    }
}
