<?php

require __DIR__ . '/../vendor/autoload.php';

use ZerosDev\TriPay\Client;
use ZerosDev\TriPay\Transaction;

$config = require __DIR__ . '/config.php';

$client = new Client($config);
$transaction = new Transaction($client);

$result = $transaction->detail('T11111111111');
echo $result->getBody()->getContents();
