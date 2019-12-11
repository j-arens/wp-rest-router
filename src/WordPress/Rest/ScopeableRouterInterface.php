<?php

declare(strict_types=1);

namespace O\WordPress\Rest;

interface ScopeableRouterInterface extends RouterInterface
{
    /**
     * @param string $scope
     * @param callable $callback
     */
    public function route(string $scope, ?callable $callback): RouterInterface;
}
