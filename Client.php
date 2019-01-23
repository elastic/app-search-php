<?php
/**
 * This file is part of the Swiftype App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swiftype\AppSearch;

/**
 * Client implementation.
 *
 * @package Swiftype\AppSearch
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class Client
{
    /**
    * @var Connections\Connection
    */
    private $connection;

    /**
     * @var callable
     */
    private $endpoint;

    public function __construct(callable $endpointBuilder, Connection\Connection $connection)
    {
        $this->endpointBuilder = $endpointBuilder;
        $this->connection      = $connection;
    }


    public function createEngine($name)
    {
        $params = [
            'name' => $name,
        ];

        $endpoint = ($this->endpointBuilder)('CreateEngine');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    public function deleteEngine($engineName)
    {
        $params = [
            'engine_name' => $engineName,
        ];

        $endpoint = ($this->endpointBuilder)('DeleteEngine');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    public function getEngine($engineName)
    {
        $params = [
            'engine_name' => $engineName,
        ];

        $endpoint = ($this->endpointBuilder)('GetEngine');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    public function indexDocuments($engineName, $body)
    {
        $params = [
            'engine_name' => $engineName,
        ];

        $endpoint = ($this->endpointBuilder)('IndexDocuments');
        $endpoint->setParams($params);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }

    public function listEngines($pageCurrent, $pageSize)
    {
        $params = [
            'page.current' => $pageCurrent,
            'page.size' => $pageSize,
        ];

        $endpoint = ($this->endpointBuilder)('ListEngines');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    public function search($engineName, $query, $pageCurrent, $pageSize)
    {
        $params = [
            'engine_name' => $engineName,
            'query' => $query,
            'page.current' => $pageCurrent,
            'page.size' => $pageSize,
        ];

        $endpoint = ($this->endpointBuilder)('Search');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    private function performRequest(Endpoint\EndpointInterface $endpoint)
    {
        $method  = $endpoint->getMethod();
        $uri     = $endpoint->getURI();
        $params  = $endpoint->getParams();
        $body    = $endpoint->getBody();

        return $this->connection->performRequest($method, $uri, $params, $body)->wait();
    }
}
