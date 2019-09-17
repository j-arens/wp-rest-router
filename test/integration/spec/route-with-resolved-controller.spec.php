<?php

describe('route with resolved controller', function () {
    beforeAll(function () {
        $this->client = client();
        loadFixture('route-with-resolved-controller');
    });

    it('should return the correct response if the route was successfully created', function () {
        $res = $this->client->get('lol');
        $data = json_decode($res->getBody()->getContents(), true);
        expect($data)->toBeA('array');
        expect($data['route-with-resolved-controller'])->toBe(true);
    });
});
