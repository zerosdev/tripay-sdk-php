<?php

require __DIR__ . '/../vendor/autoload.php';

use ZerosDev\TriPay\Client;
use ZerosDev\TriPay\Merchant;

$config = require __DIR__ . '/config.php';

$client = new Client($config);
$merchant = new Merchant($client);

$result = $merchant->feeCalculator(100000, 'BRIVA');
echo $result->getBody()->getContents();
