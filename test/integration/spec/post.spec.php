<?php

describe('POST', function () {
    beforeAll(function () {
        $this->client = client();
    });

    describe('POST route', function () {
        beforeAll(function () {
            loadFixture('post');
        });

        it('should return the correct response if the route was successfully created', function () {
            $res = $this->client->post('lol');
            $data = json_decode($res->getBody()->getContents(), true);
            expect($data)->toBeA('array');
            expect($data['post'])->toBe(true);
        });
    });

    describe('scoped post route', function () {
        beforeAll(function () {
            loadFixture('scoped-post');
        });

        it('should return the correct response if the route was successfully created', function () {
            $res = $this->client->post('scoped/lol');
            $data = json_decode($res->getBody()->getContents(), true);
            expect($data)->toBeA('array');
            expect($data['scoped-post'])->toBe(true);
        });
    });
});
