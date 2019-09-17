<?php

describe('route with controller', function () {
    beforeAll(function () {
        $this->client = client();
        loadFixture('route-with-controller');
    });

    it('should return the correct response if the route was successfully created', function () {
        $res = $this->client->get('lol');
        $data = json_decode($res->getBody()->getContents(), true);
        expect($data)->toBeA('array');
        expect($data['route-with-controller'])->toBe(true);
    });
});
