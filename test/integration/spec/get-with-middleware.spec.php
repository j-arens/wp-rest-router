<?php

describe('GET with middleware', function () {
    beforeAll(function () {
        $this->client = client();
    });

    describe('GET route with a middleware function', function () {
        beforeAll(function () {
            loadFixture('get-with-middleware');
        });

        it('should return the correct response if the route was successfully created', function () {
            $res = $this->client->get('wp-json/wp-rest-router/lol');
            $header = $res->getHeader('X-Middleware');
            expect($header[0])->toBe('true');
            $data = json_decode($res->getBody()->getContents(), true);
            expect($data)->toBeA('array');
            expect($data['get-with-middleware'])->toBe(true);
        });
    });
});
