<?php

use O\WordPress\Rest\Route;
use O\WordPress\Rest\RestException;

describe('Route', function () {
    describe('->setArg', function () {
        it('sets query parameter arguments on the route', function () {
            $arg = [
                'required' => false,
                'type' => 'array',
                'items' => [
                    'type' => 'string',
                    'enum' => [
                        'foo',
                        'bar',
                    ],
                ],
            ];
            $instance = new Route('GET', 'foo', function () {});
            $instance->setArg('lol', $arg);
            $result = $instance->toArray();
            expect($result['args']['args']['lol'])->toBe($arg);
        });
    });

    describe('->setPermission', function () {
        it('sets a permission callback on the route', function () {
            $cb = function () {};
            $instance = new Route('GET', 'foo', function () {});
            $instance->setPermission($cb);
            $result = $instance->toArray();
            expect($result['args']['permission_callback'])->toBe($cb);
        });
    });

    describe('->endpointCallback', function () {
        it('returns the provided endpoint callback if its callable', function () {
            $endpoint = function () {};
            $instance = new Route('GET', 'foo', $endpoint);
            $result = $instance->toArray();
            expect($result['args']['callback'])->toBe($endpoint);
        });

        it('returns a closure if the provided endpoint is a class@method string', function () {
            class Controller {};
            $endpoint = 'Controller@method';
            $instance = new Route('GET', 'foo', $endpoint);
            $result = $instance->toArray();
            expect($result['args']['callback'])->toBeAnInstanceOf('Closure');
        });

        it('throws if the provided endpoint is not callable and is not a string', function () {
            $endpoint = 1;
            $instance = new Route('GET', 'foo', $endpoint);
            $fn = function () use ($instance) {
                $instance->toArray();
            };
            expect($fn)->toThrow(new RestException("failed to create REST endpoint", [
                'endpoint' => $endpoint,
            ]));
        });
    });

    describe('->makeMethodClosure', function () {
        it('throws if the provided endpoint class does not exist', function () {
            $endpoint = 'Lol@method';
            $instance = new Route('GET', 'foo', $endpoint);
            $fn = function () use ($instance) {
                $instance->toArray();
            };
            expect($fn)->toThrow(new RestException(
                "failed to create REST endpoint because class Lol is non-existent"
            ));
        });

        it('returns a closure that throws if the provided method does not exist on the provided class', function () {
            class TestController {};
            $endpoint = 'TestController@test';
            $instance = new Route('GET', 'foo', $endpoint);
            $result = $instance->toArray();
            $fn = function () use ($result) {
                $result['args']['callback'](new WP_REST_Request(), new WP_REST_Response());
            };
            expect($fn)->toThrow(new RestException(
                "failed to create REST endpoint because method test on class TestController is non-existent"
            ));
        });

        it('returns a closure that instantiates the provided class and calls the provided method when invoked', function () {
            class MyController {
                public function foobar(WP_REST_Request $req, WP_REST_Response $res) {
                    throw new Exception('method invoked');
                }
            };
            $endpoint = 'MyController@foobar';
            $instance = new Route('GET', 'foo', $endpoint);
            $result = $instance->toArray();
            $fn = function () use ($result) {
                $result['args']['callback'](new WP_REST_Request(), new WP_REST_Response());
            };
            expect($fn)->toThrow(new Exception('method invoked'));
        });
    });

    describe('->resolveClass', function () {
        it('resolves classes through the provided resolver if callable', function () {
            class NewController {};
            $resolver = function (string $class) {
                throw new Exception("resolving $class");
            };
            $endpoint = 'NewController@foobar';
            $instance = new Route('GET', 'foo', $endpoint);
            $instance->setResolver($resolver);
            $result = $instance->toArray();
            $fn = function () use ($result) {
                $result['args']['callback'](new WP_REST_Request(), new WP_REST_Response());
            };
            expect($fn)->toThrow(new Exception('resolving NewController'));
        });

        it('attempts to naively create new instances of the provided class if no resolver is provided', function () {
            class NoResolver {
                public function callMeMaybe() {
                    throw new Exception("method invoked");
                }
            };
            $endpoint = 'NoResolver@callMeMaybe';
            $instance = new Route('GET', 'foo', $endpoint);
            $result = $instance->toArray();
            $fn = function () use ($result) {
                $result['args']['callback'](new WP_REST_Request(), new WP_REST_Response());
            };
            expect($fn)->toThrow(new Exception('method invoked'));
        });
    });
});
