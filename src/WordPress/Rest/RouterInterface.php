<?php declare(strict_types=1);

namespace Downshift\WordPress\Rest;

interface RouterInterface extends UsesResolverInterface, UsesMiddlewareInterface
{
    /**
     * @param string $path
     * @param mixed $endpoint
     */
    public function get(string $path, $endpoint): Route;

    /**
     * @param string $path
     * @param mixed $endpoint
     */
    public function post(string $path, $endpoint): Route;

    /**
     * @param string $path
     * @param mixed $endpoint
     */
    public function put(string $path, $endpoint): Route;

    /**
     * @param string $path
     * @param mixed $endpoint
     */
    public function patch(string $path, $endpoint): Route;

    /**
     * @param string $path
     * @param mixed $endpoint
     */
    public function delete(string $path, $endpoint): Route;

    /**
     * @return array
     */
    public function routes(): array;
}
