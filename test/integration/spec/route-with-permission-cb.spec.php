<?php

describe('route with permisson callback', function () {
    beforeAll(function () {
        $this->client = client();
        loadFixture('route-with-permission-cb');
    });

    it('should return the correct response if the route was successfully created', function () {
        $res = $this->client->get('lol');
        expect($res->getStatusCode())->toBe(401);
    });
});
