<?php

namespace Swiftype\AppSearch;

use Swiftype\AppSearch\Connection\Connection;
use Swiftype\AppSearch\Connection\Handler;

class ClientBuilder
{
    /**
     * @var string
     */
    private $apiEndpoint;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var Serializer\SerializerInterface
     */
    private $serializer;

    /**
     * @var callable
     */
    private $handler;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $tracer;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->handler    = new \GuzzleHttp\Ring\Client\CurlHandler();
        $this->serializer = new Serializer\SmartSerializer();
        $this->logger     = new \Psr\Log\NullLogger();
        $this->tracer     = new \Psr\Log\NullLogger();
    }

    /**
     * Instantiate a new client builder.
     *
     * @param string $hostIdentifier
     * @param string $apiKey
     *
     * @return \Swiftype\AppSearch\Client
     */
    public static function create($apiEndpoint = null, $apiKey = null)
    {
        return (new static())->setApiEndpoint($apiEndpoint)->setApiKey($apiKey)->build();
    }

    /**
     * Set the api key for the client.
     *
     * @param string $apiKey
     *
     * @return ClientBuilder
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Set the api endpoint for the client.
     *
     * @param string $apiEndpoint
     *
     * @return ClientBuilder
     */
    public function setApiEndpoint($apiEndpoint)
    {
        $isValidEndpoint = false;

        if (filter_var($apiEndpoint, FILTER_VALIDATE_URL)) {
            $isValidEndpoint = true;
        }

        if (!$isValidEndpoint) {
            $apiEndpoint     = sprintf('https://%s', $apiEndpoint);
            $isValidEndpoint = filter_var($apiEndpoint, FILTER_VALIDATE_URL) != false;
        }

        if (!$isValidEndpoint) {
            $apiEndpoint     = sprintf('%s.%s', $apiEndpoint, "api.swiftype.com");
            $isValidEndpoint = filter_var($apiEndpoint, FILTER_VALIDATE_URL) != false;
        }

        if (!$isValidEndpoint) {
            throw new \Exception("Invalid API endpoint : $apiEndpoint");
        }

        $this->apiEndpoint = $apiEndpoint;

        return $this;
    }

    /**
     * Return the configured Swiftype client.
     *
     * @return \Swiftype\AppSearch\Client
     */
    public function build()
    {
        return $this->instantiate();
    }

    /**
     * Instantiate the client.
     *
     * @return \Swiftype\AppSearch\Client
     */
    private function instantiate()
    {
        $this->handler = new Handler\RequestAuthenticationHandler($this->handler, $this->apiKey);
        $this->handler = new Handler\RequestUrlHandler($this->handler, $this->apiEndpoint);
        $this->handler = new Handler\RequestSerializationHandler($this->handler, $this->serializer);
        $this->handler = new Handler\ConnectionErrorHandler($this->handler);

        $connection = new Connection($this->handler, $this->serializer, $this->logger, $this->tracer);

        return new Client($this->endpointBuilder(), $connection);
    }

    /**
     * Instantiate the endpoint builder.
     *
     * @return Endpoint\Builder.
     */
    private function endpointBuilder()
    {
        return new Endpoint\Builder();
    }
}
