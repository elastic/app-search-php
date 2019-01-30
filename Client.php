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
 *
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class Client
{
    /**
     * @var Connection\Connection
     */
    private $connection;

    /**
     * @var callable
     */
    private $endpointBuilder;

    /**
     * Client constructor.
     *
     * @param callable              $endpointBuilder Allow to access endpoints.
     * @param Connection\Connection $connection      HTTP connection handler.
     */
    public function __construct(callable $endpointBuilder, Connection\Connection $connection)
    {
        $this->endpointBuilder = $endpointBuilder;
        $this->connection = $connection;
    }

    // phpcs:disable

    /**
     * Operation: createCuration.
     *
     * @param string $engineName   Name of the engine.
     * @param array  $curationData TODO
     *
     * @return array
     */
    public function createCuration($engineName, $curationData)
    {
        $params = [
            'engine_name' => $engineName,
        ];

        $endpoint = ($this->endpointBuilder)('CreateCuration');
        $endpoint->setParams($params);
        $endpoint->setBody($curationData);

        return $this->performRequest($endpoint);
    }

    /**
     * Operation: createEngine.
     *
     * @param array $engine TODO
     *
     * @return array
     */
    public function createEngine($engine)
    {
        $params = [
        ];

        $endpoint = ($this->endpointBuilder)('CreateEngine');
        $endpoint->setParams($params);
        $endpoint->setBody($engine);

        return $this->performRequest($endpoint);
    }

    /**
     * Operation: createSynonymSet.
     *
     * @param string $engineName     Name of the engine.
     * @param array  $synonymSetData TODO
     *
     * @return array
     */
    public function createSynonymSet($engineName, $synonymSetData)
    {
        $params = [
            'engine_name' => $engineName,
        ];

        $endpoint = ($this->endpointBuilder)('CreateSynonymSet');
        $endpoint->setParams($params);
        $endpoint->setBody($synonymSetData);

        return $this->performRequest($endpoint);
    }

    /**
     * Operation: deleteCuration.
     *
     * @param string $engineName Name of the engine.
     * @param string $curationId Curation id.
     *
     * @return array
     */
    public function deleteCuration($engineName, $curationId)
    {
        $params = [
            'engine_name' => $engineName,
            'curation_id' => $curationId,
        ];

        $endpoint = ($this->endpointBuilder)('DeleteCuration');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Operation: deleteDocuments.
     *
     * @param string $engineName  Name of the engine.
     * @param array  $documentIds TODO
     *
     * @return array
     */
    public function deleteDocuments($engineName, $documentIds)
    {
        $params = [
            'engine_name' => $engineName,
        ];

        $endpoint = ($this->endpointBuilder)('DeleteDocuments');
        $endpoint->setParams($params);
        $endpoint->setBody($documentIds);

        return $this->performRequest($endpoint);
    }

    /**
     * Operation: deleteEngine.
     *
     * @param string $engineName Name of the engine.
     *
     * @return array
     */
    public function deleteEngine($engineName)
    {
        $params = [
            'engine_name' => $engineName,
        ];

        $endpoint = ($this->endpointBuilder)('DeleteEngine');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Operation: deleteSynonymSet.
     *
     * @param string $engineName   Name of the engine.
     * @param string $synonymSetId Synonym set id.
     *
     * @return array
     */
    public function deleteSynonymSet($engineName, $synonymSetId)
    {
        $params = [
            'engine_name' => $engineName,
            'synonym_set_id' => $synonymSetId,
        ];

        $endpoint = ($this->endpointBuilder)('DeleteSynonymSet');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Operation: getCuration.
     *
     * @param string $engineName Name of the engine.
     * @param string $curationId Curation id.
     *
     * @return array
     */
    public function getCuration($engineName, $curationId)
    {
        $params = [
            'engine_name' => $engineName,
            'curation_id' => $curationId,
        ];

        $endpoint = ($this->endpointBuilder)('GetCuration');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Operation: getDocuments.
     *
     * @param string $engineName  Name of the engine.
     * @param array  $documentIds TODO
     *
     * @return array
     */
    public function getDocuments($engineName, $documentIds)
    {
        $params = [
            'engine_name' => $engineName,
        ];

        $endpoint = ($this->endpointBuilder)('GetDocuments');
        $endpoint->setParams($params);
        $endpoint->setBody($documentIds);

        return $this->performRequest($endpoint);
    }

    /**
     * Operation: getEngine.
     *
     * @param string $engineName Name of the engine.
     *
     * @return array
     */
    public function getEngine($engineName)
    {
        $params = [
            'engine_name' => $engineName,
        ];

        $endpoint = ($this->endpointBuilder)('GetEngine');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Operation: getSchema.
     *
     * @param string $engineName Name of the engine.
     *
     * @return array
     */
    public function getSchema($engineName)
    {
        $params = [
            'engine_name' => $engineName,
        ];

        $endpoint = ($this->endpointBuilder)('GetSchema');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Operation: getSearchSettings.
     *
     * @param string $engineName Name of the engine.
     *
     * @return array
     */
    public function getSearchSettings($engineName)
    {
        $params = [
            'engine_name' => $engineName,
        ];

        $endpoint = ($this->endpointBuilder)('GetSearchSettings');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Operation: getSynonymSet.
     *
     * @param string $engineName   Name of the engine.
     * @param string $synonymSetId Synonym set id.
     *
     * @return array
     */
    public function getSynonymSet($engineName, $synonymSetId)
    {
        $params = [
            'engine_name' => $engineName,
            'synonym_set_id' => $synonymSetId,
        ];

        $endpoint = ($this->endpointBuilder)('GetSynonymSet');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Operation: indexDocuments.
     *
     * @param string $engineName Name of the engine.
     * @param array  $documents  TODO
     *
     * @return array
     */
    public function indexDocuments($engineName, $documents)
    {
        $params = [
            'engine_name' => $engineName,
        ];

        $endpoint = ($this->endpointBuilder)('IndexDocuments');
        $endpoint->setParams($params);
        $endpoint->setBody($documents);

        return $this->performRequest($endpoint);
    }

    /**
     * Operation: listCurations.
     *
     * @param string $engineName Name of the engine.
     * @param array  $params     TODO
     *
     * @return array
     */
    public function listCurations($engineName, $params = null)
    {
        $params = [
            'engine_name' => $engineName,
        ];

        $endpoint = ($this->endpointBuilder)('ListCurations');
        $endpoint->setParams($params);
        $endpoint->setBody($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Operation: listDocuments.
     *
     * @param string $engineName Name of the engine.
     * @param array  $params     TODO
     *
     * @return array
     */
    public function listDocuments($engineName, $params = null)
    {
        $params = [
            'engine_name' => $engineName,
        ];

        $endpoint = ($this->endpointBuilder)('ListDocuments');
        $endpoint->setParams($params);
        $endpoint->setBody($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Operation: listEngines.
     *
     * @param array $params TODO
     *
     * @return array
     */
    public function listEngines($params = null)
    {
        $params = [
        ];

        $endpoint = ($this->endpointBuilder)('ListEngines');
        $endpoint->setParams($params);
        $endpoint->setBody($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Operation: listSynonyms.
     *
     * @param string $engineName Name of the engine.
     * @param array  $params     TODO
     *
     * @return array
     */
    public function listSynonyms($engineName, $params = null)
    {
        $params = [
            'engine_name' => $engineName,
        ];

        $endpoint = ($this->endpointBuilder)('ListSynonyms');
        $endpoint->setParams($params);
        $endpoint->setBody($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Operation: multiSearch.
     *
     * @param string $engineName Name of the engine.
     * @param array  $queries    TODO
     *
     * @return array
     */
    public function multiSearch($engineName, $queries)
    {
        $params = [
            'engine_name' => $engineName,
        ];

        $endpoint = ($this->endpointBuilder)('MultiSearch');
        $endpoint->setParams($params);
        $endpoint->setBody($queries);

        return $this->performRequest($endpoint);
    }

    /**
     * Operation: resetSearchSettings.
     *
     * @param string $engineName Name of the engine.
     *
     * @return array
     */
    public function resetSearchSettings($engineName)
    {
        $params = [
            'engine_name' => $engineName,
        ];

        $endpoint = ($this->endpointBuilder)('ResetSearchSettings');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Operation: search.
     *
     * @param string $engineName    Name of the engine.
     * @param array  $searchRequest TODO
     *
     * @return array
     */
    public function search($engineName, $searchRequest)
    {
        $params = [
            'engine_name' => $engineName,
        ];

        $endpoint = ($this->endpointBuilder)('Search');
        $endpoint->setParams($params);
        $endpoint->setBody($searchRequest);

        return $this->performRequest($endpoint);
    }

    /**
     * Operation: sendClick.
     *
     * @param string $engineName Name of the engine.
     * @param array  $clickData  TODO
     *
     * @return array
     */
    public function sendClick($engineName, $clickData)
    {
        $params = [
            'engine_name' => $engineName,
        ];

        $endpoint = ($this->endpointBuilder)('SendClick');
        $endpoint->setParams($params);
        $endpoint->setBody($clickData);

        return $this->performRequest($endpoint);
    }

    /**
     * Operation: updateCuration.
     *
     * @param string $engineName   Name of the engine.
     * @param string $curationId   Curation id.
     * @param array  $curationData TODO
     *
     * @return array
     */
    public function updateCuration($engineName, $curationId, $curationData)
    {
        $params = [
            'engine_name' => $engineName,
            'curation_id' => $curationId,
        ];

        $endpoint = ($this->endpointBuilder)('UpdateCuration');
        $endpoint->setParams($params);
        $endpoint->setBody($curationData);

        return $this->performRequest($endpoint);
    }

    /**
     * Operation: updateDocuments.
     *
     * @param string $engineName Name of the engine.
     * @param array  $documents  TODO
     *
     * @return array
     */
    public function updateDocuments($engineName, $documents)
    {
        $params = [
            'engine_name' => $engineName,
        ];

        $endpoint = ($this->endpointBuilder)('UpdateDocuments');
        $endpoint->setParams($params);
        $endpoint->setBody($documents);

        return $this->performRequest($endpoint);
    }

    /**
     * Operation: updateSchema.
     *
     * @param string $engineName Name of the engine.
     * @param array  $schema     TODO
     *
     * @return array
     */
    public function updateSchema($engineName, $schema)
    {
        $params = [
            'engine_name' => $engineName,
        ];

        $endpoint = ($this->endpointBuilder)('UpdateSchema');
        $endpoint->setParams($params);
        $endpoint->setBody($schema);

        return $this->performRequest($endpoint);
    }

    /**
     * Operation: updateSearchSettings.
     *
     * @param string $engineName     Name of the engine.
     * @param array  $searchSettings TODO
     *
     * @return array
     */
    public function updateSearchSettings($engineName, $searchSettings)
    {
        $params = [
            'engine_name' => $engineName,
        ];

        $endpoint = ($this->endpointBuilder)('UpdateSearchSettings');
        $endpoint->setParams($params);
        $endpoint->setBody($searchSettings);

        return $this->performRequest($endpoint);
    }

    // phpcs:enable

    private function performRequest(Endpoint\EndpointInterface $endpoint)
    {
        $method = $endpoint->getMethod();
        $uri = $endpoint->getURI();
        $params = $endpoint->getParams();
        $body = $endpoint->getBody();

        $response = $this->connection->performRequest($method, $uri, $params, $body)->wait();

        return $response['body'] ?? $response;
    }
}
