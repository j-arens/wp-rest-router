<?php

use Downshift\WordPress\Rest\ScopedRouter;

describe('ScopedRouter', function () {
    beforeEach(function () {
        $this->instance = new ScopedRouter('lol');
    });

    describe('->routes', function () {
        it('returns an array of routes that can be registered with wordpress', function () {
            $this->instance->get('', function () {});
            $this->instance->post('', function () {});
            $this->instance->put('', function () {});
            $this->instance->patch('', function () {});
            $this->instance->delete('', function () {});
            $result = $this->instance->routes();
            expect($result)->toBeA('array');
            expect(count($result))->toBe(5);
        });

        it('wraps route callbacks in a response provider', function () {
            $this->instance->get('', function (WP_REST_Request $req, WP_REST_Response $res) {
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
