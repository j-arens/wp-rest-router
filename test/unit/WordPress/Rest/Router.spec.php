<?php

use Downshift\WordPress\Rest\Router;
use Downshift\WordPress\Rest\ScopedRouter;

describe('Router', function () {
    beforeEach(function () {
        $this->instance = new Router();
    });

    describe('->route', function () {
        it('creates and returns a scoped router object', function () {
            $result = $this->instance->route('lol');
            expect($result)->toBeAnInstanceOf('Downshift\WordPress\Rest\ScopedRouter');
        });

        it('sets a resolver on the scoped router if one has been provided', function () {
            $resolver = function () {};
            $this->instance->setResolver($resolver);
            $result = $this->instance->route('lol');
            expect($result->getResolver())->toBe($resolver);
        });

        it('invokes the callback if provided', function () {
            $fn = function () {
                $cb = function (ScopedRouter $router) {
                    throw new Exception('callback invoked');
                };
                $this->instance->route('lol', $cb);
            };
            expect($fn)->toThrow(new Exception('callback invoked'));
        });
    });

    describe('->routes', function () {
        it('returns an array of routes that can be registered with wordpress', function () {
            $this->instance->get('lol', function () {});
            $this->instance->post('lol', function () {});
            $this->instance->put('lol', function () {});
            $this->instance->patch('lol', function () {});
            $this->instance->delete('lol', function () {});
            $this->instance->route('foo', function (ScopedRouter $router) {
                $router->get('bar', function () {});
                $router->post('bar', function () {});
                $router->put('bar', function () {});
                $router->patch('bar', function () {});
                $router->delete('bar', function () {});
            });
            $result = $this->instance->routes();
            expect($result)->toBeA('array');
            expect(count($result))->toBe(10);
        });

        it('wraps route callbacks in a response provider', function () {
            $this->instance->get('lol', function (WP_REST_Request $req, WP_REST_Response $res) {
                return $res;
            });
            $routes = $this->instance->routes();
            $callback = $routes[0]['args']['callback'];
            $result = $callback(new WP_REST_Request());
            expect($result)->toBeAnInstanceOf('WP_REST_Response');
        });

        it('wraps route callbacks in a try catch', function () {
            $this->instance->get('lol', function (WP_REST_Request $req, WP_REST_Response $res) {
                throw new Exception('callback');
            });
            $routes = $this->instance->routes();
            $result = $routes[0]['args']['callback'](new WP_REST_Request());
            expect($result)->toBeAnInstanceOf('WP_Error');
        });

        it('applies middleware to route callbacks', function () {
            $this->instance->use(function (WP_REST_Request $req, WP_REST_Response $res, callable $next) {
                $res->lol = true;
                return;
            });
            $this->instance->get('lol', function (WP_REST_Request $req, WP_REST_Response $res) {
                throw new Exception('callback');
            });
            $routes = $this->instance->routes();
            $result = $routes[0]['args']['callback'](new WP_REST_Request());
            expect($result->lol)->toBe(true);
        });
    });
});
