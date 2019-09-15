<?php

describe('GET with short circuit middleware', function () {
    beforeAll(function () {
        $this->client = client();
    });

    describe('GET route with a middleware function that short circuits', function () {
        beforeAll(function () {
            loadFixture('get-with-short-circuit-middleware');
        });

        it('should return the correct response if the route was successfully created', function () {
            $res = $this->client->get('wp-json/wp-rest-router/lol');
            $header = $res->getHeader('X-Middleware');
            expect($header[0])->toBe('true');
            $data = json_decode($res->getBody()->getContents(), true);
            expect($data)->toBeNull();
        });
    });
});
