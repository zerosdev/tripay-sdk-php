<?php

require __DIR__ . '/../vendor/autoload.php';

use ZerosDev\TriPay\Client;
use ZerosDev\TriPay\OpenPayment;

$config = require __DIR__ . '/config.php';

$client = new Client($config);
$openPayment = new OpenPayment($client);

$result = $openPayment->create([
    'method' => 'BSIVAOP',
    'merchant_ref' => 'USER-123',
    'customer_name' => 'Nama Pelanggan',
]);
echo $result->getBody()->getContents();
