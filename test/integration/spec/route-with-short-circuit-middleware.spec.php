<?php

describe('route with a middleware that short circuits', function () {
    beforeAll(function () {
        $this->client = client();
        loadFixture('route-with-short-circuit-middleware');
    });

    it('should return the correct response if the route was successfully created', function () {
        $res = $this->client->get('lol');
        $header = $res->getHeader('X-Middleware');
        expect($header[0])->toBe('true');
        $data = json_decode($res->getBody()->getContents(), true);
        expect($data)->toBeNull();
    });
});
