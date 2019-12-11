<?php

use Kahlan\Plugin\Double;
use O\WordPress\Rest\CallbackWrappers;

describe('CallbackWrappers', function () {
    beforeEach(function () {
        $dbl = Double::instance(['uses' => 'O\WordPress\Rest\CallbackWrappers']);
        $this->instance = new $dbl();

        // ya, i know this is gross ¯\_(ツ)_/¯

        $tryCatchRef = new ReflectionMethod($this->instance, 'tryCatch');
        $tryCatchRef->setAccessible(true);
        $this->tryCatch = function (...$args) use ($tryCatchRef) {
            return call_user_func_array(
                [$tryCatchRef, 'invoke'],
                array_merge([$this->instance], $args)
            );
        };

        $resProviderRef = new ReflectionMethod($this->instance, 'provideResponse');
        $resProviderRef->setAccessible(true);
        $this->provideResponse = function (...$args) use ($resProviderRef) {
            return call_user_func_array(
                [$resProviderRef, 'invoke'],
                array_merge([$this->instance], $args)
            );
        };
    });

    describe('->tryCatch', function () {
        it('catches errors and returns an instance of WP_Error', function () {
            $fn = $this->tryCatch(function ($req) {
                throw new Exception('lol');
            });
            $result = $fn(new WP_REST_Request());
            expect($result)->toBeAnInstanceOf('WP_Error');
        });
    });

    describe('->provideResponse', function () {
        it('creates a new response object and calls the callback with it if none provided', function () {
            $callback = function (WP_REST_Request $req, WP_REST_Response $res) {
                return $res;
            };
            $closure = $this->provideResponse($callback);
            $result = $closure(new WP_REST_Request());
            expect($result)->toBeAnInstanceOf('WP_REST_Response');
        });

        it('passes along provided response objects', function () {
            $res = new WP_REST_Response();
            $callback = function (WP_REST_Request $req, WP_REST_Response $res) {
                return $res;
            };
            $closure = $this->provideResponse($callback);
            $result = $closure(new WP_REST_Request(), $res);
            expect($result)->toBe($res);
        });
    });
});
