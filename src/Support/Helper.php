<?php

namespace ZerosDev\TriPay\Support;

use Exception;
use InvalidArgumentException;
use ZerosDev\TriPay\Client;

class Helper
{
    /**
     * Create signature
     *
     * @param ZerosDev\TriPay\Client $client
     * @param array $payloads
     * @return string
     */
    public static function makeSignature(Client $client, array $payloads): string
    {
        $merchantRef = isset($payloads['merchant_ref']) ? $payloads['merchant_ref'] : null;
        $amount = self::formatAmount($payloads['amount']);

        $payloads['amount'] = self::formatAmount($payloads['amount']);
        return hash_hmac('sha256', $client->merchantCode . $merchantRef . $amount, $client->privateKey);
    }

    /**
     * Create open payment signature
     *
     * @param ZerosDev\TriPay\Client $client
     * @param array $payloads
     * @return string
     */
    public static function makeOpenPaymentSignature(Client $client, array $payloads): string
    {
        $method = isset($payloads['method']) ? $payloads['method'] : null;
        $merchantRef = isset($payloads['merchant_ref']) ? $payloads['merchant_ref'] : null;

        return hash_hmac('sha256', $client->merchantCode . $method . $merchantRef, $client->privateKey);
    }

    /**
     * Format amount to integer
     *
     * @param mixed $amount
     * @return int
     */
    public static function formatAmount($amount): int
    {
        if (!is_numeric($amount)) {
            throw new Exception('Amount must be numeric value');
        }

        return (int) number_format($amount, 0, '', '');
    }

    /**
     * Check required key in payloads
     *
     * @param array $requireds
     * @param array $payloads
     * @return void
     * @throws InvalidArgumentException
     */
    public static function checkRequiredPayloads(array $requireds, array $payloads): void
    {
        foreach ($requireds as $req) {
            if (!isset($payloads[$req]) || empty($payloads[$req])) {
                throw new InvalidArgumentException("`{$req}` must be filled in payloads");
            }
        }
    }
}
