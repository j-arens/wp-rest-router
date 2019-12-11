<?php

use O\WordPress\Rest\ScopedRouter;

describe('ScopedRouter', function () {
    beforeEach(function () {
        $this->instance = new ScopedRouter('lol-scope');
    });

    describe('->routes', function () {
        it('returns an array of routes', function () {
            $this->instance->get('', function () {});
            $this->instance->post('', function () {});
            $this->instance->put('', function () {});
            $this->instance->patch('', function () {});
            $this->instance->delete('', function () {});
            $result = $this->instance->routes();
            expect($result)->toBeA('array');
            expect(count($result))->toBe(5);
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
            $result = $routes[0]['args']['callback'](new WP_REST_Request(), new WP_REST_Response());
            expect($result->lol)->toBe(true);
        });
    });
});
