<?php

describe('DELETE', function () {
    beforeAll(function () {
        $this->client = client();
    });

    describe('delete route', function () {
        beforeAll(function () {
            loadFixture('delete');
        });

        it('should return the correct response if the route was successfully created', function () {
            $res = $this->client->delete('lol');
            $data = json_decode($res->getBody()->getContents(), true);
            expect($data)->toBeA('array');
            expect($data['delete'])->toBe(true);
        });
    });

    describe('scoped delete route', function () {
        beforeAll(function () {
            loadFixture('scoped-delete');
        });

        it('should return the correct response if the route was successfully created', function () {
            $res = $this->client->delete('scoped/lol');
            $data = json_decode($res->getBody()->getContents(), true);
            expect($data)->toBeA('array');
            expect($data['scoped-delete'])->toBe(true);
        });
    });
});
