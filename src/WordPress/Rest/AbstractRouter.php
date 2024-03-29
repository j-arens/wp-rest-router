<?php

declare(strict_types=1);

namespace O\WordPress\Rest;

abstract class AbstractRouter implements RouterInterface
{
    use CallbackWrappers;
    use UsesResolver;

    /**
     * @var string
     */
    public const GET = 'GET';

    /**
     * @var string
     */
    public const POST = 'POST';

    /**
     * @var string
     */
    public const PUT = 'PUT';

    /**
     * @var string
     */
    public const PATCH = 'PATCH';

    /**
     * @var string
     */
    public const DELETE = 'DELETE';

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
    public function use(callable $middleware): void
    {
        $this->middlewares[] = $middleware;
    }
}
