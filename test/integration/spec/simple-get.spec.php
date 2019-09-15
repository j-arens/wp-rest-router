<?php

describe('Simple GET', function () {
    beforeAll(function () {
        $this->client = client();
    });

    describe('GET route with function callback', function () {
        beforeAll(function () {
            loadFixture('simple-get');
        });

        it('should return the correct response if the route was successfully created', function () {
            $res = $this->client->get('wp-json/wp-rest-router/lol');
            $data = json_decode($res->getBody()->getContents(), true);
            expect($data)->toBeA('array');
            expect($data['simple-get'])->toBe(true);
        });
    });
});
