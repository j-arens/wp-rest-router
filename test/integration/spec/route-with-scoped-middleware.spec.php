<?php

describe('route with scoped middleware', function () {
    beforeAll(function () {
        $this->client = client();
        loadFixture('route-with-scoped-middleware');
    });

    it('should return the correct response if the route was successfully created', function () {
        $res = $this->client->get('scoped/lol');
        $header = $res->getHeader('X-Middleware');
        expect($header[0])->toBe('true');
        $data = json_decode($res->getBody()->getContents(), true);
        expect($data)->toBeA('array');
        expect($data['route-with-scoped-middleware'])->toBe(true);
    });
});
