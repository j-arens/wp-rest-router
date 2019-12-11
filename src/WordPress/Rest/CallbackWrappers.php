<?php

declare(strict_types=1);

namespace O\WordPress\Rest;

use Exception;
use Closure;
use WP_REST_Request;
use WP_REST_Response;
use WP_Error;

trait CallbackWrappers
{
    /**
     * @param callable $fn
     * @return Closure
     */
    protected function tryCatch(callable $fn): Closure
    {
        return function (WP_REST_Request $req) use ($fn) {
            try {
                return $fn($req);
            } catch (Exception $e) {
                $data = method_exists($e, 'toArray') ? $e->toArray() : [
                    'type' => get_class($e),
                    'message' => $e->getMessage(),
                    'data' => [],
                ];
                return new WP_Error(
                    'rest_error',
                    'caught error while handling REST request',
                    $data
                );
            }
        };
    }

    /**
     *
     */
    protected function provideResponse(callable $fn): Closure
    {
        return function (WP_REST_Request $req, ?WP_REST_Response $res = null) use ($fn) {
            if (is_null($res)) {
                $res = new WP_REST_Response();
            }
            return $fn($req, $res);
        };
    }
}
