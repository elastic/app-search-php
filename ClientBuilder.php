<?php
/**
 * This file is part of the Swiftype App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swiftype\AppSearch;

use Swiftype\AppSearch\Connection\Connection;
use Swiftype\AppSearch\Connection\Handler;
use Swiftype\AppSearch\Exception\UnexpectedValueException;

/**
 * Use this class to instantiate new client and all their dependencies.
 *
 * @package Swiftype\AppSearch
 *
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
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
        $this->handler = new \GuzzleHttp\Ring\Client\CurlHandler();
        $this->serializer = new Serializer\SmartSerializer();
        $this->logger = new \Psr\Log\NullLogger();
        $this->tracer = new \Psr\Log\NullLogger();
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
        return (new static())->setApiEndpoint($apiEndpoint)->setApiKey($apiKey);
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
        $testedEndpoint = $apiEndpoint;

        if (filter_var($testedEndpoint, FILTER_VALIDATE_URL)) {
            $isValidEndpoint = true;
        }

        if (!$isValidEndpoint) {
            $testedEndpoint = sprintf('https://%s', $testedEndpoint);
            $isValidEndpoint = false != filter_var($testedEndpoint, FILTER_VALIDATE_URL);
        }

        if (!$isValidEndpoint) {
            $testedEndpoint = sprintf('%s.%s', $testedEndpoint, 'api.swiftype.com');
            $isValidEndpoint = false != filter_var($testedEndpoint, FILTER_VALIDATE_URL);
        }

        if (!$isValidEndpoint) {
            throw new UnexpectedValueException("Invalid API endpoint : $apiEndpoint");
        }

        $this->apiEndpoint = $testedEndpoint;

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
        $this->handler = new Handler\ResponseSerializationHandler($this->handler, $this->serializer);
        $this->handler = new Handler\ApiErrorHandler($this->handler);

        $connection = new Connection($this->handler, $this->logger, $this->tracer);

        return new Client($this->endpointBuilder(), $connection);
    }

    /**
     * Instantiate the endpoint builder.
     *
     * @return endpoint\Builder
     */
    private function endpointBuilder()
    {
        return new Endpoint\Builder();
    }
}
