<?php

namespace ZerosDev\TriPay;

use Exception;
use UnexpectedValueException;
use ZerosDev\TriPay\Exception\SignatureException;
use ZerosDev\TriPay\Support\Constant;

class Callback
{
    /**
     * Client instance
     *
     * @var Client
     */
    protected Client $client;

    /**
     * Callback json data
     *
     * @var string
     */
    protected string $json = "";

    /**
     * Parsed callback json data
     *
     * @var object|null
     */
    protected ?object $parsedJson;

    /**
     * Enable/disable debug mode
     *
     * @var boolean
     */
    protected bool $debug = false;

    /**
     * Callback instance
     *
     * @param Client $client
     * @param bool $verifyOnLoad
     */
    public function __construct(Client $client)
    {
        if (!function_exists('file_get_contents')) {
            throw new Exception('`file_get_contents` function is disabled on your system. Please contact your hosting provider');
        }

        $this->client = $client;
        $this->json = (string) file_get_contents("php://input");
        $this->parsedJson = json_decode($this->json);
    }

    /**
     * Enable debugging
     *
     * !! WARNING !!
     * Only enable it while debugging.
     * Leaving it enabled can lead to security vulnerabilities
     *
     * @return self
     */
    public function enableDebug(): self
    {
        $this->debug = true;
        return $this;
    }

    /**
     * Get local signature
     *
     * @return string
     */
    public function localSignature(): string
    {
        return (string) hash_hmac('sha256', $this->json, $this->client->privateKey);
    }

    /**
     * Get incoming signature
     *
     * @return string|null
     */
    public function incomingSignature(): ?string
    {
        return (string) (isset($_SERVER['HTTP_X_CALLBACK_SIGNATURE']) ? $_SERVER['HTTP_X_CALLBACK_SIGNATURE'] : "");
    }

    /**
     * Validate incoming signature
     *
     * @return boolean
     * @throws SignatureException
     * @throws UnexpectedValueException
     */
    public function validate(): bool
    {
        $localSignature = $this->localSignature();
        $incomingSignature = $this->incomingSignature();

        $validSignature = hash_equals(
            $localSignature,
            $incomingSignature
        );

        if (!$validSignature) {
            $message = 'Incoming signature does not match local signature';
            if ($this->debug) {
                $message .= ': local(' . $localSignature . ') vs incoming(' . $incomingSignature . ')';
            }
            throw new SignatureException($message);
        }

        $validData = !is_null($this->data());

        if (!$validData) {
            throw new UnexpectedValueException('Callback data is invalid. Invalid or empty JSON');
        }

        return true;
    }

    /**
     * Parse JSON data
     *
     * @return object|null
     */
    public function data(): ?object
    {
        return $this->parsedJson;
    }
}
