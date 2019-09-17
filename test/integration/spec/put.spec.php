<?php

describe('PUT', function () {
    beforeAll(function () {
        $this->client = client();
    });

    describe('PUT route', function () {
        beforeAll(function () {
            loadFixture('put');
        });

        it('should return the correct response if the route was successfully created', function () {
            $res = $this->client->put('lol');
            $data = json_decode($res->getBody()->getContents(), true);
            expect($data)->toBeA('array');
            expect($data['put'])->toBe(true);
        });
    });

    describe('scoped put route', function () {
        beforeAll(function () {
            loadFixture('scoped-put');
        });

        it('should return the correct response if the route was successfully created', function () {
            $res = $this->client->put('scoped/lol');
            $data = json_decode($res->getBody()->getContents(), true);
            expect($data)->toBeA('array');
            expect($data['scoped-put'])->toBe(true);
        });
    });
});
