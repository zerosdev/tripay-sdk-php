<h1 align="center">tripay-sdk-php</h1>
<h6 align="center">Unofficial TriPay.co.id Integration Kit for PHP</h6>

<p align="center">
  <img src="https://img.shields.io/github/v/release/zerosdev/tripay-sdk-php?include_prereleases" alt="release"/>
  <img src="https://img.shields.io/github/languages/top/zerosdev/tripay-sdk-php" alt="language"/>
  <img src="https://img.shields.io/github/license/zerosdev/tripay-sdk-php" alt="license"/>
  <img src="https://img.shields.io/github/languages/code-size/zerosdev/tripay-sdk-php" alt="size"/>
  <img src="https://img.shields.io/github/downloads/zerosdev/tripay-sdk-php/total" alt="downloads"/>
  <img src="https://img.shields.io/badge/PRs-welcome-brightgreen.svg" alt="pulls"/>
</p>

## Requirements
- PHP v7.2.5+
- PHP JSON Extension
- PHP cURL Extension

## Installation

1. Run command
```
composer require zerosdev/tripay-sdk-php
```

## Usage

```php
<?php

require 'path/to/your/vendor/autoload.php';

use ZerosDev\TriPay\Client as TriPayClient;
use ZerosDev\TriPay\Support\Constant;
use ZerosDev\TriPay\Support\Helper;
use ZerosDev\TriPay\Transaction;

$merchantCode = 'T12345';
$apiKey = 'd1cfd***********888ed3';
$privateKey = 'd1cfd***********888ed3';
$mode = Constant::MODE_DEVELOPMENT;
$guzzleOptions = []; // Your additional Guzzle options (https://docs.guzzlephp.org/en/stable/request-options.html)

$client = new TriPayClient($merchantCode, $apiKey, $privateKey, $mode, $guzzleOptions);
$transaction = new Transaction($client);

/**
 * `amount` will be calculated automatically from order items
 * so you don't have to enter it
 * In this example, amount will be 40.000
 */
$result = $transaction
    ->addOrderItem('Gula', 10000, 1)
    ->addOrderItem('Kopi', 6000, 5)
    ->create([
        'method' => 'BRIVA',
        'merchant_ref' => 'INV123',
        'customer_name' => 'Nama Pelanggan',
        'customer_email' => 'email@konsumen.id',
        'customer_phone' => '081234567890',
        'expired_time' => Helper::makeTimestamp('6 HOUR'),
    ]);

echo $result->getBody()->getContents();

/**
* For debugging purpose
*/
$debugs = $client->debugs();
echo json_encode($debugs, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
```

Please check the `/examples` for the other examples
