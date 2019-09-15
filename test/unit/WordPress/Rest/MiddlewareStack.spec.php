<?php

use Kahlan\Plugin\Double;
use Downshift\Wordpress\Rest\MiddlewareStack;

describe('MiddlewareStack', function () {
    describe('->apply', function () {
        it('wraps the given callback in a closure that provides WP_REST_Request and WP_REST_Response as arguments', function () {
            $dbl = Double::instance(['methods' => 'callback']);
            allow($dbl)->toReceive('callback')->andRun(function (WP_REST_Request $req, WP_REST_Response $res) {});
            expect($dbl)->toReceive('callback');
            $instance = new MiddlewareStack([]);
            $closure = $instance->apply([$dbl, 'callback']);
            $closure(new WP_REST_Request(), new WP_REST_Response());
        });

        it('returns a closure that does not invoke the callback if the middleware stack is not completed', function () {
            $middleware1 = function (WP_REST_Request $req, WP_REST_Response $res) {return;};
            $middleware2 = function (WP_REST_Request $req, WP_REST_Response $res, callable $next) {next();};
            $dbl = Double::instance(['methods' => 'callback']);
            allow($dbl)->toReceive('callback')->andRun(function (WP_REST_Request $req, WP_REST_Response $res) {});
            expect($dbl)->not->toReceive('callback');
            $instance = new MiddlewareStack([$middleware1, $middleware2]);
            $closure = $instance->apply([$dbl, 'callback']);
            $closure(new WP_REST_Request(), new WP_REST_Response());
        });

        it('returns a closure that returns the result from the provided callback', function () {
            $dbl = Double::instance(['methods' => 'callback']);
            allow($dbl)->toReceive('callback')->andRun(function (WP_REST_Request $req, WP_REST_Response $res) {
                return $res;
            });
            $instance = new MiddlewareStack([]);
            $closure = $instance->apply([$dbl, 'callback']);
            $result = $closure(new WP_REST_Request(), new WP_REST_Response());
            expect($result)->toBeAnInstanceOf('WP_REST_Response');
        });
    });

    describe('->next', function () {
        it('bails if there is no middleware at the current index', function () {
            $instance = new MiddlewareStack([]);
            $result = $instance->next();
            expect($result)->toBe(true);
        });

        it('iterates through each middleware and invokes them', function () {
            $middleware1 = function (WP_REST_Request $req, WP_REST_Response $res, callable $next) {
                $res->m1 = true;
                $next();
            };
            $middleware2 = function (WP_REST_Request $req, WP_REST_Response $res, callable $next) {
                $res->m2 = true;
                $next();
            };
            $callback = function (WP_REST_Request $req, WP_REST_Response $res) {
                return $res;
            };
            $instance = new MiddlewareStack([$middleware1, $middleware2]);
            $closure = $instance->apply($callback);
            $result = $closure(new WP_REST_Request(), new WP_REST_Response());
            expect($result->m1)->toBe(true);
            expect($result->m2)->toBe(true);
        });

        it('returns false if the stack is not completed', function () {
            $middleware1 = function (WP_REST_Request $req, WP_REST_Response $res) {return;};
            $middleware2 = function (WP_REST_Request $req, WP_REST_Response $res, callable $next) {next();};
            $callback = function (WP_REST_Request $req, WP_REST_Response $res) {};
            $instance = new MiddlewareStack([$middleware1, $middleware2]);
            $instance->apply(function () {});
            $instance->req = new WP_REST_Request();
            $instance->res = new WP_REST_Response();
            $result = $instance->next();
            expect($result)->toBe(false);
        });
    });
});
