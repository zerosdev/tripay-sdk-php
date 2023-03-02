<?php

namespace ZerosDev\TriPay;

use GuzzleHttp\Psr7\Response;
use ZerosDev\TriPay\Support\Helper;

class Merchant
{
    /**
     * Client instance
     *
     * @var Client
     */
    protected Client $client;

    /**
     * Merchant instance
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get enabled payment channels
     *
     * @return Response
     */
    public function paymentChannels(): Response
    {
        return $this->client->get('merchant/payment-channel');
    }

    /**
     * Get fee calculation
     *
     * @param mixed $amount
     * @param string|null $channelCode
     * @return Response
     */
    public function feeCalculator($amount, ?string $channelCode = null): Response
    {
        $payloads = [
            'amount' => Helper::formatAmount($amount),
        ];

        if (!empty($channelCode)) {
            $payloads['code'] = $channelCode;
        }

        return $this->client->get('merchant/fee-calculator?' . http_build_query($payloads));
    }

    /**
     * Get merchant transactions
     *
     * @param array $payloads
     * @return Response
     */
    public function transactions(array $payloads = []): Response
    {
        return $this->client->get('merchant/transactions?' . http_build_query($payloads));
    }
}
