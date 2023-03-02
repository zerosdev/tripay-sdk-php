<?php

namespace ZerosDev\TriPay;

use GuzzleHttp\Psr7\Response;
use ZerosDev\TriPay\Support\Helper;

class Transaction
{
    /**
     * Client instance
     *
     * @var Client
     */
    protected Client $client;

    /**
     * Order items
     *
     * @var array
     */
    protected array $order_items = [];

    /**
     * Transaction instance
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Add order items to payloads
     *
     * @param string $name
     * @param integer $price
     * @param integer $quantity
     * @param string|null $sku
     * @param string|null $product_url
     * @param string|null $image_url
     * @return self
     */
    public function addOrderItem(string $name, int $price, int $quantity, ?string $sku = null, ?string $product_url = null, ?string $image_url = null): self
    {
        $this->order_items[] = [
            'sku' => $sku,
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity,
            'product_url' => $product_url,
            'image_url' => $image_url,
        ];

        return $this;
    }

    /**
     * Create transaction
     *
     * @param array $payloads
     * @return Response
     * @throws InvalidArgumentException
     */
    public function create(array $payloads): Response
    {
        unset($payloads['signature']);

        $payloads['order_items'] = isset($payloads['order_items'])
            ? array_merge($payloads['order_items'], $this->order_items)
            : $this->order_items;

        $payloads['amount'] = 0;
        foreach ($this->order_items as $orderItem) {
            $payloads['amount'] += $orderItem['price'] * $orderItem['quantity'];
        }

        Helper::checkRequiredPayloads([
            'method', 'customer_name', 'customer_email', 'order_items'
        ], $payloads);

        $payloads['signature'] = Helper::makeSignature($this->client, $payloads);

        return $this->client->post('transaction/create', $payloads);
    }

    /**
     * Get transaction detail
     *
     * @param string $reference
     * @return Response
     */
    public function detail(string $reference): Response
    {
        return $this->client->get('transaction/detail?reference=' . $reference);
    }
}
