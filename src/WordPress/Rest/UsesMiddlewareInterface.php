<?php

declare(strict_types=1);

namespace O\WordPress\Rest;

interface UsesMiddlewareInterface
{
    /**
     * @param callable $middleware
     */
    public function use(callable $middleware): void;
}
