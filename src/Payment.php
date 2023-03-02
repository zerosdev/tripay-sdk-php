<?php

namespace ZerosDev\TriPay;

use GuzzleHttp\Psr7\Response;
use ZerosDev\TriPay\Support\Helper;

class Payment
{
    /**
     * Client instance
     *
     * @var Client
     */
    protected Client $client;

    /**
     * Payment instance
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get payment channel instruction
     *
     * @param string $channelCode
     * @param string|null $payCode
     * @param mixed $amount
     * @param integer|null $allowHtml
     * @return Response
     */
    public function instruction(string $channelCode, ?string $payCode = null, $amount = null, ?int $allowHtml = null): Response
    {
        $payloads = [
            'code' => $channelCode
        ];

        if (!is_null($payCode)) {
            $payloads['pay_code'] = $payCode;
        }

        if (!is_null($amount)) {
            $payloads['amount'] = $amount;
        }

        if (!is_null($allowHtml)) {
            $payloads['allow_html'] = $allowHtml;
        }

        return $this->client->get('payment/instruction?' . http_build_query($payloads));
    }
}
