<?php

namespace Swiftype\AppSearch;

use Swiftype\AppSearch\Connection\Connection;

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

    private $logger;

    private $tracer;

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
     * @return \Swiftype\AppSearch\ClientBuilder
     */
    public static function create($apiEndpoint = null, $apiKey = null)
    {
        return (new static())->setApiEndpoint($apiEndpoint)->setApiKey($apiKey)->instantiate();
    }

    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

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
        $connectionParams = $this->getConnectionParams();
        $connection       = new Connection(
            $this->handler, $this->serializer, $this->logger, $this->tracer, $connectionParams
        );

        return new Client($this->endpointBuilder(), $connection);
    }

    private function getConnectionParams()
    {
        $connectionParams = [];

        if (!empty($this->apiKey)) {
            $connectionParams['headers']['Authorization'][] = sprintf("Bearer %s", $this->apiKey);
        }

        if (!empty($this->apiEndpoint)) {
            $urlComponents = parse_url($this->apiEndpoint);
            $connectionParams['scheme'] = $urlComponents['scheme'];
            $connectionParams['headers']['host'][] = $urlComponents['host'];
        }

        return $connectionParams;
    }

    private function endpointBuilder()
    {
        return new Endpoint\Builder();
    }
}
