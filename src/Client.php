<?php

namespace ZerosDev\TriPay;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\TransferStats;
use InvalidArgumentException;
use ZerosDev\TriPay\Support\Constant;

class Client
{
    /**
     * Merchant Code
     *
     * @var string|null
     */
    public ?string $merchantCode;

    /**
     * API Key
     *
     * @var string|null
     */
    public ?string $apiKey;

    /**
     * Private Key
     *
     * @var string|null
     */
    public ?string $privateKey;

    /**
     * API mode
     *
     * @var string|null
     */
    public ?string $mode;

    /**
     * Request payloads
     *
     * @var array
     */
    public array $payloads = [];

    /**
     * Debugs payload
     *
     * @var array
     */
    public array $debugs = [
        'request' => null,
        'response' => null,
    ];

    /**
     * Required configuration key
     *
     * @var array
     */
    private array $requiredConfigKeys = [
        'merchant_code',
        'api_key',
        'private_key',
        'mode'
    ];

    /**
     * Reserved guzzle options that can't be overrided
     *
     * @var array
     */
    private array $reservedGuzzleOptions = [
        'base_uri',
        'on_stats',
    ];

    /**
     * HTTP Client instance
     *
     * @var GuzzleHttp\Client as HttpClient
     */
    private HttpClient $client;

    /**
     * Client instance
     *
     * You can use array config with the following keys
     * [
     *      'merchant_id' => '',
     *      'api_key' => '',
     *      'private_key' => '',
     *      'mode => '',
     *      'guzzle_options' => []
     * ]
     *
     * or use the positional arguments in the following sequence
     * $merchantId, $apiKey, $privateKey, $mode, $guzzleOptions
     *
     * @param array|string ...$args
     * @throws InvalidArgumentException
     */
    public function __construct(...$args)
    {
        if (is_array($args[0])) {
            foreach ($this->requiredConfigKeys as $configKey) {
                if (!isset($args[0][$configKey])) {
                    throw new InvalidArgumentException("`{$configKey}` must be in the configuration value");
                }
            }
        } else {
            foreach ($this->requiredConfigKeys as $key => $configKey) {
                if (!isset($args[$key])) {
                    throw new InvalidArgumentException("`{$configKey}` must be in the configuration value");
                }
            }
        }

        $this->merchantCode = (string) is_array($args[0]) ? $args[0]['merchant_code'] : $args[0];
        $this->apiKey = (string) is_array($args[0]) ? $args[0]['api_key'] : $args[1];
        $this->privateKey = (string) is_array($args[0]) ? $args[0]['private_key'] : $args[1];
        $this->mode = (string) is_array($args[0]) ? $args[0]['mode'] : $args[2];

        $baseUri = ($this->mode == Constant::MODE_DEVELOPMENT)
            ? Constant::URL_DEVELOPMENT
            : Constant::URL_PRODUCTION;

        $options = [
            'base_uri' => $baseUri,
            'http_errors' => false,
            'connect_timeout' => 10,
            'timeout' => 50,
            'on_stats' => function (TransferStats $stats) {
                $hasResponse = $stats->hasResponse();
                $this->debugs = array_merge($this->debugs, [
                    'request' => [
                        'url' => (string) $stats->getEffectiveUri(),
                        'method' => $stats->getRequest()->getMethod(),
                        'headers' => (array) $stats->getRequest()->getHeaders(),
                        'body' => (string) $stats->getRequest()->getBody(),
                    ],
                    'response' => [
                        'status' => (int) ($hasResponse ? $stats->getResponse()->getStatusCode() : 0),
                        'headers' => (array) ($hasResponse ? $stats->getResponse()->getHeaders() : []),
                        'body' => (string) ($hasResponse ? $stats->getResponse()->getBody() : ""),
                    ],
                ]);
            },
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'User-Agent' => 'zerosdev/tripay-sdk-php',
            ]
        ];

        $guzzleOptions = (array) is_array($args[0]) ? ($args[0]['guzzle_options'] ?? []) : ($args[3] ?? []);

        foreach ($this->reservedGuzzleOptions as $reserved) {
            unset($guzzleOptions[$reserved]);
        }

        $options = array_merge($options, $guzzleOptions);

        $this->client = $this->createHttpClient($options);
    }

    private function createHttpClient(array $options): HttpClient
    {
        return new HttpClient($options);
    }

    public function get($endpoint, array $headers = []): Response
    {
        return $this->client->get($endpoint, [
            'headers' => $headers,
        ]);
    }

    public function post($endpoint, array $payloads, array $headers = []): Response
    {
        return $this->client->post($endpoint, [
            'json' => $payloads,
            'headers' => $headers,
        ]);
    }

    public function debugs(): object
    {
        return (object) $this->debugs;
    }
}
