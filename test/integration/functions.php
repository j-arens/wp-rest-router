<?php

use GuzzleHttp\Client;

const CONT_NAME = 'wp-rest-router-wordpress';
const CONT_URL = 'http://localhost:8150';
const FIX_DIR = __DIR__ . '/fixtures';
const PLUGIN_DIR = '/var/www/html/wp-content/plugins/wp-rest-router';

function loadFixture(string $fixture)
{
    $path = FIX_DIR . "/$fixture.php";
    if (!file_exists($path)) {
        throw new Exception("fixture $fixture not found");
    }
    $cmd = "docker cp $path " . CONT_NAME . ':' . PLUGIN_DIR . "/fixture.php 2>&1";
    exec($cmd, $output, $status);
    if ($status !== 0) {
        throw new Exception("failed to copy fixture $fixture to container: $output");
    }
}

function client(): Client
{
    return new Client([
        'base_uri' => CONT_URL . '/wp-json/wp-rest-router/',
        'http_errors' => false,
    ]);
}