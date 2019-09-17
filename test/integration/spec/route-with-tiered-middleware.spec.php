<?php

describe('route with tiered middleware', function () {
    beforeAll(function () {
        $this->client = client();
        loadFixture('route-with-tiered-middleware');
    });

    it('should return the correct response if the route was successfully created', function () {
        $res = $this->client->get('scoped/lol');
        $header1 = $res->getHeader('X-M1');
        expect($header1[0])->toBe('true');
        $header2 = $res->getHeader('X-M2');
        expect($header2[0])->toBe('true');
        $data = json_decode($res->getBody()->getContents(), true);
        expect($data)->toBeA('array');
        expect($data['route-with-tiered-middleware'])->toBe(true);
    });
});
