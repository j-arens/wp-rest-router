<?php declare(strict_types=1);

namespace Downshift\WordPress\Rest;

interface UsesMiddlewareInterface
{
    /**
     * @param callable $middleware
     */
    public function use(callable $middleware);
}
