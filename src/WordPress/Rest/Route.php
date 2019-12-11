<?php

declare(strict_types=1);

namespace O\WordPress\Rest;

use WP_REST_Request;
use WP_REST_Response;
use Closure;

class Route implements RouteInterface
{
    use UsesResolver;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var mixed
     */
    protected $endpoint;

    /**
     * @var array
     */
    protected $args = [];

    /**
     * @var callable
     */
    protected $permissionCallback;

    /**
     * @var callable
     */
    protected $resolver;

    /**
     * @param string $method
     * @param string $path
     * @param mixed $endpoint
     */
    public function __construct(
        string $method,
        string $path,
        $endpoint
    ) {
        $this->method = $method;
        $this->path = $path;
        $this->endpoint = $endpoint;
    }

    /**
     * {@inheritdoc}
     */
    public function setArg(string $name, array $config): Route
    {
        $this->args[$name] = $config;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setPermission(callable $callback): Route
    {
        $this->permissionCallback = $callback;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        $args = [
            'methods' => $this->method,
            'callback' => $this->endpointCallback(),
            'permission_callback' => $this->permissionCallback,
            'args' => $this->args,
        ];
        return [
            'path' => $this->path,
            'args' => $args,
        ];
    }

    /**
     * @return callable
     */
    protected function endpointCallback(): callable
    {
        if (is_callable($this->endpoint)) {
            return $this->endpoint;
        }
        if (is_string($this->endpoint)) {
            return $this->makeMethodClosure();
        }
        throw new RestException("failed to create REST endpoint", [
            'endpoint' => $this->endpoint,
        ]);
    }

    /**
     * @return Closure
     */
    protected function makeMethodClosure(): Closure
    {
        [$class, $method] = explode('@', $this->endpoint);
        if (!class_exists($class)) {
            throw new RestException(
                "failed to create REST endpoint because class $class is non-existent"
            );
        }
        return function (WP_REST_Request $req, WP_REST_Response $res) use ($class, $method) {
            $instance = $this->resolveClass($class);
            if (!method_exists($instance, $method)) {
                throw new RestException(
                    "failed to create REST endpoint because method $method on class $class is non-existent"
                );
            }
            return $instance->$method($req, $res);
        };
    }

    /**
     * @return Object
     */
    protected function resolveClass(string $class)
    {
        // use resolver if possible, allows end users to provide
        // instances resolved through their DI container if using one
        $resolver = $this->getResolver();
        if (is_callable($resolver)) {
            return $resolver($class);
        }
        // attempt to naively create new instance without any arguments
        return new $class();
    }
}
