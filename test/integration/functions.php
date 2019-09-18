<?php

use GuzzleHttp\Client;

const CONT_NAME = 'wp-rest-router-wordpress';
const CONT_URL = 'http://localhost:80';
const FIX_DIR = __DIR__ . '/fixtures';
const PLUGIN_DIR = '/var/www/html/wp-content/plugins/wp-rest-router';

function loadFixture(string $fixture)
{
    $source = FIX_DIR . "/$fixture.php";
    if (!file_exists($source)) {
        throw new Exception("fixture $fixture not found");
    }

    $dest = PLUGIN_DIR . '/fixture.php';
    if (!copy($source, $dest)) {
        throw new Exception("failed to copy fixture $fixture");
    }
}

function client(): Client
{
    return new Client([
        'base_uri' => CONT_URL . '/wp-json/wp-rest-router/',
        'http_errors' => false,
    ]);
}