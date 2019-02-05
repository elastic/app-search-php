<?php
/**
 * This file is part of the Swiftype PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swiftype\AppSearch;

/**
 * Client implementation.
 *
 * @package Swiftype
 *
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class Client extends \Swiftype\AbstractClient
{
    // phpcs:disable

    /**
     * Create a new curation.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/curations#create
     *
     * @param string $engineName   Name of the engine.
     * @param array  $curationData Curation data.
     *
     * @return array
     */
    public function createCuration($engineName, $curationData)
    {
        $params = [
            'engine_name' => $engineName,
        ];

        $endpoint = $this->getEndpoint('CreateCuration');
        $endpoint->setParams($params);
        $endpoint->setBody($curationData);

        return $this->performRequest($endpoint);
    }

    /**
     * Creates a new engine.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/engines#create
     *
     * @param array $engine Engine data.
     *
     * @return array
     */
    public function createEngine($engine)
    {
        $params = [
        ];

        $endpoint = $this->getEndpoint('CreateEngine');
        $endpoint->setParams($params);
        $endpoint->setBody($engine);

        return $this->performRequest($endpoint);
    }

    /**
     * Create a new synonym set.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/synonyms#create
     *
     * @param string $engineName     Name of the engine.
     * @param array  $synonymSetData Synonym set data.
     *
     * @return array
     */
    public function createSynonymSet($engineName, $synonymSetData)
    {
        $params = [
            'engine_name' => $engineName,
        ];

        $endpoint = $this->getEndpoint('CreateSynonymSet');
        $endpoint->setParams($params);
        $endpoint->setBody($synonymSetData);

        return $this->performRequest($endpoint);
    }

    /**
     * Delete a curation by id.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/curations#destroy
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

        $endpoint = $this->getEndpoint('DeleteCuration');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Delete documents by id.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/documents#partial
     *
     * @param string $engineName  Name of the engine.
     * @param array  $documentIds List of document ids.
     *
     * @return array
     */
    public function deleteDocuments($engineName, $documentIds)
    {
        $params = [
            'engine_name' => $engineName,
        ];

        $endpoint = $this->getEndpoint('DeleteDocuments');
        $endpoint->setParams($params);
        $endpoint->setBody($documentIds);

        return $this->performRequest($endpoint);
    }

    /**
     * Delete an engine by name.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/engines#delete
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

        $endpoint = $this->getEndpoint('DeleteEngine');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Delete a synonym set by id.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/synonyms#delete
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

        $endpoint = $this->getEndpoint('DeleteSynonymSet');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Retrieve a curation by id.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/curations#single
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

        $endpoint = $this->getEndpoint('GetCuration');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Retrieves one or more documents by id.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/documents#get
     *
     * @param string $engineName  Name of the engine.
     * @param array  $documentIds List of document ids.
     *
     * @return array
     */
    public function getDocuments($engineName, $documentIds)
    {
        $params = [
            'engine_name' => $engineName,
        ];

        $endpoint = $this->getEndpoint('GetDocuments');
        $endpoint->setParams($params);
        $endpoint->setBody($documentIds);

        return $this->performRequest($endpoint);
    }

    /**
     * Retrieves an engine by name.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/engines#get
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

        $endpoint = $this->getEndpoint('GetEngine');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Retrieve current schema for then engine.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/schema#read
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

        $endpoint = $this->getEndpoint('GetSchema');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Retrive current search settings for the engine.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/search-settings#show
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

        $endpoint = $this->getEndpoint('GetSearchSettings');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Retrieve a synonym set by id.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/synonyms#list-one
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

        $endpoint = $this->getEndpoint('GetSynonymSet');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Create or update documents.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/documents#create
     *
     * @param string $engineName Name of the engine.
     * @param array  $documents  List of documents.
     *
     * @return array
     */
    public function indexDocuments($engineName, $documents)
    {
        $params = [
            'engine_name' => $engineName,
        ];

        $endpoint = $this->getEndpoint('IndexDocuments');
        $endpoint->setParams($params);
        $endpoint->setBody($documents);

        return $this->performRequest($endpoint);
    }

    /**
     * Retrieve available curations for the engine.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/curations#read
     *
     * @param string $engineName Name of the engine.
     * @param array  $listParams Listing params (include page[current] and page[size]).
     *
     * @return array
     */
    public function listCurations($engineName, $listParams = null)
    {
        $params = [
            'engine_name' => $engineName,
        ];

        $endpoint = $this->getEndpoint('ListCurations');
        $endpoint->setParams($params);
        $endpoint->setBody($listParams);

        return $this->performRequest($endpoint);
    }

    /**
     * List all available documents with optional pagination support.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/documents#list
     *
     * @param string $engineName Name of the engine.
     * @param array  $listParams Listing params (include page[current] and page[size]).
     *
     * @return array
     */
    public function listDocuments($engineName, $listParams = null)
    {
        $params = [
            'engine_name' => $engineName,
        ];

        $endpoint = $this->getEndpoint('ListDocuments');
        $endpoint->setParams($params);
        $endpoint->setBody($listParams);

        return $this->performRequest($endpoint);
    }

    /**
     * Retrieves all engines with optional pagination support.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/engines#list
     *
     * @param array $listParams Listing params (include page[current] and page[size]).
     *
     * @return array
     */
    public function listEngines($listParams = null)
    {
        $params = [
        ];

        $endpoint = $this->getEndpoint('ListEngines');
        $endpoint->setParams($params);
        $endpoint->setBody($listParams);

        return $this->performRequest($endpoint);
    }

    /**
     * Retrieve available synonym sets for the engine.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/synonyms#get
     *
     * @param string $engineName Name of the engine.
     * @param array  $listParams Listing params (include page[current] and page[size]).
     *
     * @return array
     */
    public function listSynonymSets($engineName, $listParams = null)
    {
        $params = [
            'engine_name' => $engineName,
        ];

        $endpoint = $this->getEndpoint('ListSynonymSets');
        $endpoint->setParams($params);
        $endpoint->setBody($listParams);

        return $this->performRequest($endpoint);
    }

    /**
     * Run several search in the same request.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/search#multi
     *
     * @param string $engineName Name of the engine.
     * @param array  $queries    Array of search requests.
     *
     * @return array
     */
    public function multiSearch($engineName, $queries)
    {
        $params = [
            'engine_name' => $engineName,
        ];

        $endpoint = $this->getEndpoint('MultiSearch');
        $endpoint->setParams($params);
        $endpoint->setBody($queries);

        return $this->performRequest($endpoint);
    }

    /**
     * Reset search settings for the engine.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/search-settings#reset
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

        $endpoint = $this->getEndpoint('ResetSearchSettings');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Allows you to search over, facet and filter your data.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/search
     *
     * @param string $engineName    Name of the engine.
     * @param array  $searchRequest Search request.
     *
     * @return array
     */
    public function search($engineName, $searchRequest)
    {
        $params = [
            'engine_name' => $engineName,
        ];

        $endpoint = $this->getEndpoint('Search');
        $endpoint->setParams($params);
        $endpoint->setBody($searchRequest);

        return $this->performRequest($endpoint);
    }

    /**
     * Send data about clicked results.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/clickthrough
     *
     * @param string $engineName Name of the engine.
     * @param array  $clickData  Click data (include query text and document id).
     *
     * @return array
     */
    public function sendClick($engineName, $clickData)
    {
        $params = [
            'engine_name' => $engineName,
        ];

        $endpoint = $this->getEndpoint('SendClick');
        $endpoint->setParams($params);
        $endpoint->setBody($clickData);

        return $this->performRequest($endpoint);
    }

    /**
     * Update an existing curation.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/curations#update
     *
     * @param string $engineName   Name of the engine.
     * @param string $curationId   Curation id.
     * @param array  $curationData Curation data.
     *
     * @return array
     */
    public function updateCuration($engineName, $curationId, $curationData)
    {
        $params = [
            'engine_name' => $engineName,
            'curation_id' => $curationId,
        ];

        $endpoint = $this->getEndpoint('UpdateCuration');
        $endpoint->setParams($params);
        $endpoint->setBody($curationData);

        return $this->performRequest($endpoint);
    }

    /**
     * Partial update of documents.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/documents#partial
     *
     * @param string $engineName Name of the engine.
     * @param array  $documents  List of documents.
     *
     * @return array
     */
    public function updateDocuments($engineName, $documents)
    {
        $params = [
            'engine_name' => $engineName,
        ];

        $endpoint = $this->getEndpoint('UpdateDocuments');
        $endpoint->setParams($params);
        $endpoint->setBody($documents);

        return $this->performRequest($endpoint);
    }

    /**
     * Update schema for the current engine.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/schema#patch
     *
     * @param string $engineName Name of the engine.
     * @param array  $schema     Schema description.
     *
     * @return array
     */
    public function updateSchema($engineName, $schema)
    {
        $params = [
            'engine_name' => $engineName,
        ];

        $endpoint = $this->getEndpoint('UpdateSchema');
        $endpoint->setParams($params);
        $endpoint->setBody($schema);

        return $this->performRequest($endpoint);
    }

    /**
     * Update search settings for the engine.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/search-settings#update
     *
     * @param string $engineName     Name of the engine.
     * @param array  $searchSettings Search settings.
     *
     * @return array
     */
    public function updateSearchSettings($engineName, $searchSettings)
    {
        $params = [
            'engine_name' => $engineName,
        ];

        $endpoint = $this->getEndpoint('UpdateSearchSettings');
        $endpoint->setParams($params);
        $endpoint->setBody($searchSettings);

        return $this->performRequest($endpoint);
    }

    // phpcs:enable
}
