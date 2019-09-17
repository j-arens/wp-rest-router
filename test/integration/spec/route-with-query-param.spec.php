<?php

describe('route with query parameter', function () {
    beforeAll(function () {
        $this->client = client();
        loadFixture('route-with-query-param');
    });

    it('should return the correct response if the route was successfully created', function () {
        $res = $this->client->get('lol?param=1234');
        $data = json_decode($res->getBody()->getContents(), true);
        expect($data['param'])->toBe(1234);
    });
});
