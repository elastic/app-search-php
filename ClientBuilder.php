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
class ClientBuilder extends \Swiftype\AbstractClientBuilder
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
     * Instantiate a new client builder.
     *
     * @param string $hostIdentifier
     * @param string $apiKey
     *
     * @return \Swiftype\AppSearch\ClientBuilder
     */
    public static function create($apiEndpoint = null, $apiKey = null)
    {
        return (new static())->setHost($apiEndpoint)->setApiKey($apiKey);
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
     * @param string $host
     *
     * @return ClientBuilder
     */
    public function setHost($host)
    {
        $isValidEndpoint = false;
        $testedEndpoint = $host;

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
            throw new \Swiftype\Exception\UnexpectedValueException("Invalid API endpoint : $host");
        }

        return parent::setHost($testedEndpoint);
    }

    /**
     * Return the configured Swiftype client.
     *
     * @return \Swiftype\AppSearch\Client
     */
    public function build()
    {
        return new Client($this->getEndpointBuilder(), $this->getConnection());
    }

    /**
     * {@inheritdoc}
     */
    protected function getHandler()
    {
        $handler = parent::getHandler();
        $handler = new Connection\Handler\RequestAuthenticationHandler($handler, $this->apiKey);
        $handler = new \Swiftype\Connection\Handler\RequestUrlPrefixHandler($handler, self::URI_PREFIX);
        $handler = new Connection\Handler\ApiErrorHandler($handler);

        return $handler;
    }

    /**
     * {@inheritdoc}
     */
    protected function getEndpointBuilder()
    {
        return new \Swiftype\Endpoint\Builder(__NAMESPACE__ . "\Endpoint");
    }
}
