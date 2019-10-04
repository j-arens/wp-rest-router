<?php

declare(strict_types=1);

namespace Downshift\WordPress\Rest;

interface RouteInterface extends UsesResolverInterface
{
    /**
     * @param string $param
     * @param array $config
     * @return Route
     */
    public function setArg(string $param, array $config): Route;

    /**
     * @param callable $callback
     * @return Route
     */
    public function setPermission(callable $callback): Route;

    /**
     * @return array
     */
    public function toArray(): array;
}
