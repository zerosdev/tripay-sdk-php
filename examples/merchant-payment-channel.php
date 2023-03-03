<?php

require __DIR__ . '/../vendor/autoload.php';

use ZerosDev\TriPay\Client;
use ZerosDev\TriPay\Merchant;

$config = require __DIR__ . '/config.php';

$client = new Client($config);
$merchant = new Merchant($client);

$result = $merchant->paymentChannels();
echo $result->getBody()->getContents();
