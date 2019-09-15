<?php declare(strict_types=1);

namespace Downshift\WordPress\Rest;

use \Closure;

interface MiddlewareStackInterface
{
    /**
     * @param callable $callback
     * @return Closure
     */
    public function apply(callable $callback): Closure;

    /**
     * Iterates through the middleware stack
     */
    public function next(): bool;
}
