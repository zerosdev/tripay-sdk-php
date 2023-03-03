<?php

require __DIR__ . '/../vendor/autoload.php';

use ZerosDev\TriPay\Client;
use ZerosDev\TriPay\OpenPayment;

$config = require __DIR__ . '/config.php';

$client = new Client($config);
$openPayment = new OpenPayment($client);

$result = $openPayment->detail('T1234OP234GDGT4');
echo $result->getBody()->getContents();
