<?php

describe('route with error', function () {
    beforeAll(function () {
        $this->client = client();
        loadFixture('route-with-error');
    });

    it('should return the correct response if the error was caught', function () {
        $res = $this->client->get('lol');
        $data = json_decode($res->getBody()->getContents(), true);
        expect($data)->toBeA('array');
        expect($data['message'])->toBe('caught error while handling REST request');
        expect($data['data']['type'])->toBe('Downshift\WordPress\Rest\RestException');
        expect($data['data']['message'])->toBe('error');
        expect($data['data']['data']['foo'])->toBe(true);
        expect($data['data']['data']['bar'])->toBe(false);
    });
});
