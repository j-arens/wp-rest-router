<?php

describe('GET with permisson callback', function () {
    beforeAll(function () {
        $this->client = client();
    });

    describe('GET route with a permission callback', function () {
        beforeAll(function () {
            loadFixture('get-with-permission-cb');
        });

        it('should return the correct response if the route was successfully created', function () {
            $res = $this->client->get('wp-json/wp-rest-router/lol');
            expect($res->getStatusCode())->toBe(401);
        });
    });
});
