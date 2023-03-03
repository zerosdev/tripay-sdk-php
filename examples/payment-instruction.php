<?php

require __DIR__ . '/../vendor/autoload.php';

use ZerosDev\TriPay\Client;
use ZerosDev\TriPay\Payment;

$config = require __DIR__ . '/config.php';

$client = new Client($config);
$payment = new Payment($client);

$result = $payment->instruction('BRIVA');
echo $result->getBody()->getContents();
