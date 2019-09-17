<?php

describe('PATCH', function () {
    beforeAll(function () {
        $this->client = client();
    });

    describe('patch route', function () {
        beforeAll(function () {
            loadFixture('patch');
        });

        it('should return the correct response if the route was successfully created', function () {
            $res = $this->client->patch('lol');
            $data = json_decode($res->getBody()->getContents(), true);
            expect($data)->toBeA('array');
            expect($data['patch'])->toBe(true);
        });
    });

    describe('scoped patch route', function () {
        beforeAll(function () {
            loadFixture('scoped-patch');
        });

        it('should return the correct response if the route was successfully created', function () {
            $res = $this->client->patch('scoped/lol');
            $data = json_decode($res->getBody()->getContents(), true);
            expect($data)->toBeA('array');
            expect($data['scoped-patch'])->toBe(true);
        });
    });
});
