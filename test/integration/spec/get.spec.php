<?php

describe('GET', function () {
    beforeAll(function () {
        $this->client = client();
    });

    describe('get route', function () {
        beforeAll(function () {
            loadFixture('get');
        });

        it('should return the correct response if the route was successfully created', function () {
            $res = $this->client->get('lol');
            $data = json_decode($res->getBody()->getContents(), true);
            expect($data)->toBeA('array');
            expect($data['get'])->toBe(true);
        });
    });

    describe('scoped get route', function () {
        beforeAll(function () {
            loadFixture('scoped-get');
        });

        it('should return the correct response if the route was successfully created', function () {
            $res = $this->client->get('scoped/lol');
            $data = json_decode($res->getBody()->getContents(), true);
            expect($data)->toBeA('array');
            expect($data['scoped-get'])->toBe(true);
        });
    });
});
