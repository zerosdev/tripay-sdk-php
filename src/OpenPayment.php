<?php

namespace ZerosDev\TriPay;

use GuzzleHttp\Psr7\Response;
use ZerosDev\TriPay\Support\Helper;

class OpenPayment
{
    /**
     * Client instance
     *
     * @var Client
     */
    protected Client $client;

    /**
     * OpenPayment instance
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Create open payment
     *
     * @param array $payloads
     * @return Response
     * @throws InvalidArgumentException
     */
    public function create(array $payloads): Response
    {
        unset($payloads['signature']);

        Helper::checkRequiredPayloads([
            'method'
        ], $payloads);

        $payloads['signature'] = Helper::makeOpenPaymentSignature($this->client, $payloads);

        return $this->client->post('open-payment/create', $payloads);
    }

    /**
     * Get open payment detail
     *
     * @param string $uuid
     * @return Response
     */
    public function detail(string $uuid): Response
    {
        return $this->client->get('open-payment/' . $uuid . '/detail');
    }

    /**
     * Get open payment transactions
     *
     * @param string $uuid
     * @return Response
     */
    public function transactions(string $uuid): Response
    {
        return $this->client->get('open-payment/' . $uuid . '/transactions');
    }
}
