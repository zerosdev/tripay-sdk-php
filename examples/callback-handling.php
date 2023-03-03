<?php

require __DIR__ . '/../vendor/autoload.php';

use Exception;
use ZerosDev\TriPay\Client;
use ZerosDev\TriPay\Callback;

$config = require __DIR__ . '/config.php';

$client = new Client($config);
$callback = new Callback($client);

/**
 * Enable debugging
 *
 * !! WARNING !!
 * Only enable it while debugging.
 * Leaving it enabled can lead to security compromise
 */
$callback->enableDebug();

/**
 * Run validation
 * It will throws Exception when validation fail
 */
try {
    $callback->validate();
} catch (Exception $e) {
    echo $e->getMessage();
    die;
}

/**
 * Get callback data as object
 */
$data = $callback->data();

print_r($data);
