<?php
/**
 * This file is part of the Swiftype App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swiftype\AppSearch;

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
    private const URI_PREFIX = '/api/as/v1/';

    /**
     * @var string
     */
    private $apiEndpoint;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var \Swiftype\Serializer\SerializerInterface
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
        $this->serializer = new \Swiftype\Serializer\SmartSerializer();
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
            throw new \Swiftype\Exception\UnexpectedValueException("Invalid API endpoint : $apiEndpoint");
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
        $this->handler = new Connection\Handler\RequestAuthenticationHandler($this->handler, $this->apiKey);
        $this->handler = new \Swiftype\Connection\Handler\RequestUrlHandler($this->handler, $this->apiEndpoint, self::URI_PREFIX);
        $this->handler = new \Swiftype\Connection\Handler\RequestSerializationHandler($this->handler, $this->serializer);
        $this->handler = new \Swiftype\Connection\Handler\ConnectionErrorHandler($this->handler);
        $this->handler = new \Swiftype\Connection\Handler\ResponseSerializationHandler($this->handler, $this->serializer);
        $this->handler = new Connection\Handler\ApiErrorHandler($this->handler);

        $connection = new \Swiftype\Connection\Connection($this->handler, $this->logger, $this->tracer);

        return new Client($this->endpointBuilder(), $connection);
    }

    /**
     * Instantiate the endpoint builder.
     *
     * @return \Swiftype\Endpoint\Builder
     */
    private function endpointBuilder()
    {
        return new \Swiftype\Endpoint\Builder(__NAMESPACE__ . "\Endpoint");
    }
}
