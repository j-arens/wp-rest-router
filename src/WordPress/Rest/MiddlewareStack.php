<?php

declare(strict_types=1);

namespace O\WordPress\Rest;

use Closure;
use WP_REST_Request;
use WP_REST_Response;

class MiddlewareStack implements MiddlewareStackInterface
{
    /**
     * @var array
     */
    protected $middlewares = [];

    /**
     * @var int
     */
    protected $index = 0;

    /**
     * @var WP_REST_Request
     */
    public $req;

    /**
     * @var WP_REST_Response
     */
    public $res;

    /**
     * @param array $middlewares
     */
    public function __construct(array $middlewares)
    {
        $this->middlewares = $middlewares;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(callable $callback): Closure
    {
        return function (WP_REST_Request $req, WP_REST_Response $res) use ($callback) {
            $this->req = $req;
            $this->res = $res;

            $done = $this->next();
            // if a middleware stack is not completed then assume
            // that the intention is to stop and return the response
            if (!$done) {
                return $this->res;
            }
            return $callback($this->req, $this->res);
        };
    }

    /**
     * {@inheritdoc}
     */
    public function next(): bool
    {
        if (!isset($this->middlewares[$this->index])) {
            return true;
        }
        $this->middlewares[$this->index]($this->req, $this->res, function () {
            $this->index += 1;
            $this->next();
        });
        // all middlewares called next, stack is completed
        if ($this->index >= count($this->middlewares)) {
            return true;
        }
        // stack was not completed
        return false;
    }
}
