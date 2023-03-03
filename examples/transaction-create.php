<?php

require __DIR__ . '/../vendor/autoload.php';

use ZerosDev\TriPay\Client;
use ZerosDev\TriPay\Support\Helper;
use ZerosDev\TriPay\Transaction;

$config = require __DIR__ . '/config.php';

$client = new Client($config);
$transaction = new Transaction($client);

/**
 * `amount` will be calculated automatically from order items
 * In this example, amount will be 43.000
 */
$result = $transaction
    ->addOrderItem('Gula', 10000, 1)
    ->addOrderItem('Kopi', 5000, 3)
    ->addOrderItem('Teh', 3000, 1)
    ->addOrderItem('Nasi', 15000, 1)
    ->create([
        'method' => 'BRIVA',
        'merchant_ref' => 'INV123',
        'customer_name' => 'Nama Pelanggan',
        'customer_email' => 'email@konsumen.id',
        'customer_phone' => '081234567890',
        'return_url' => 'https://example.com/return',
        'expired_time' => Helper::makeTimestamp('1 DAY')
    ]);
echo $result->getBody()->getContents();
