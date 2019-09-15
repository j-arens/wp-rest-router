<?php

describe('GET with controller', function () {
    beforeAll(function () {
        $this->client = client();
    });

    describe('GET route with controller endpoint', function () {
        beforeAll(function () {
            loadFixture('get-with-controller');
        });

        it('should return the correct response if the route was successfully created', function () {
            $res = $this->client->get('wp-json/wp-rest-router/lol');
            $data = json_decode($res->getBody()->getContents(), true);
            expect($data)->toBeA('array');
            expect($data['get-with-controller'])->toBe(true);
        });
    });
});
