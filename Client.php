<?php
/**
 * This file is part of the Elastic App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elastic\AppSearch\Client;

/**
 * Client implementation.
 *
 * @package Elastic\AppSearch\Client
 */
class Client extends \Elastic\OpenApi\Codegen\AbstractClient
{
    // phpcs:disable

    /**
     * Create a new API key.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/credentials#create
     *
     * @param string $name             API key name.
     * @param string $type             Type of API key.
     * @param bool   $read             Sets whether the key can read from the engine(s).
     * @param bool   $write            Sets whether the key can write to the engine(s).
     * @param bool   $accessAllEngines Applies to all present and future engines. If true, do not include the engines parameter, otherwise the engines parameter is required.
     * @param array  $engines          If access_all_engines is set to false, provide the engines that the key is permitted to access.
     *
     * @return array
     */
    public function createCredential($name, $type, $read, $write, $accessAllEngines = false, $engines = null)
    {
        $params = [
            'name' => $name,
            'type' => $type,
            'read' => $read,
            'write' => $write,
            'access_all_engines' => $accessAllEngines,
            'engines' => $engines,
        ];

        $endpoint = $this->getEndpoint('CreateCredential');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Create a new curation.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/curations#create
     *
     * @param string $engineName     Name of the engine.
     * @param array  $queries        List of affected search queries.
     * @param array  $promotedDocIds List of promoted document ids.
     * @param array  $hiddenDocIds   List of hidden document ids.
     *
     * @return array
     */
    public function createCuration($engineName, $queries, $promotedDocIds = null, $hiddenDocIds = null)
    {
        $params = [
            'engine_name' => $engineName,
            'queries' => $queries,
            'promoted' => $promotedDocIds,
            'hidden' => $hiddenDocIds,
        ];

        $endpoint = $this->getEndpoint('CreateCuration');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Creates a new engine.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/engines#create
     *
     * @param string $name     Engine name.
     * @param string $language Engine language (null for universal).
     *
     * @return array
     */
    public function createEngine($name, $language = null)
    {
        $params = [
            'name' => $name,
            'language' => $language,
        ];

        $endpoint = $this->getEndpoint('CreateEngine');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Create a new synonym set.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/synonyms#create
     *
     * @param string $engineName Name of the engine.
     * @param array  $synonyms   List of synonyms words.
     *
     * @return array
     */
    public function createSynonymSet($engineName, $synonyms)
    {
        $params = [
            'engine_name' => $engineName,
            'synonyms' => $synonyms,
        ];

        $endpoint = $this->getEndpoint('CreateSynonymSet');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Delete an API key.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/credentials#delete
     *
     * @param string $keyName API key name.
     *
     * @return array
     */
    public function deleteCredential($keyName)
    {
        $params = [
            'key_name' => $keyName,
        ];

        $endpoint = $this->getEndpoint('DeleteCredential');
        $endpoint->setParams($params);

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
     * The API Log displays API request and response data at the Engine level.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/logs
     *
     * @param string $engineName       Name of the engine.
     * @param string $fromDate         Filter date from.
     * @param string $toDate           Filter date to.
     * @param string $currentPage      The page to fetch. Defaults to 1.
     * @param string $pageSize         The number of results per page.
     * @param string $query            Use this to specify a particular endpoint, like analytics, search, curations and so on.
     * @param string $httpStatusFilter Filter based on a particular status code: 400, 401, 403, 429, 200.
     * @param string $httpMethodFilter Filter based on a particular HTTP method: GET, POST, PUT, PATCH, DELETE.
     * @param string $sortDirection    Would you like to have your results ascending, oldest to newest, or descending, newest to oldest?
     *
     * @return array
     */
    public function getApiLogs($engineName, $fromDate, $toDate, $currentPage = null, $pageSize = null, $query = null, $httpStatusFilter = null, $httpMethodFilter = null, $sortDirection = null)
    {
        $params = [
            'engine_name' => $engineName,
            'filters.date.from' => $fromDate,
            'filters.date.to' => $toDate,
            'page.current' => $currentPage,
            'page.size' => $pageSize,
            'query' => $query,
            'filters.status' => $httpStatusFilter,
            'filters.method' => $httpMethodFilter,
            'sort_direction' => $sortDirection,
        ];

        $endpoint = $this->getEndpoint('GetApiLogs');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Returns the number of clicks and total number of queries over a period.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/analytics/counts
     *
     * @param string $engineName Name of the engine.
     * @param array  $filters    Analytics filters
     * @param string $interval   You can define an interval along with your date range. Can be either hour or day.
     *
     * @return array
     */
    public function getCountAnalytics($engineName, $filters = null, $interval = null)
    {
        $params = [
            'engine_name' => $engineName,
            'filters' => $filters,
            'interval' => $interval,
        ];

        $endpoint = $this->getEndpoint('GetCountAnalytics');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Retrieve details for one API key.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/credentials#single
     *
     * @param string $keyName API key name.
     *
     * @return array
     */
    public function getCredential($keyName)
    {
        $params = [
            'key_name' => $keyName,
        ];

        $endpoint = $this->getEndpoint('GetCredential');
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
     * Returns the number of clicks received by a document in descending order.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/analytics/clicks
     *
     * @param string $engineName Name of the engine.
     * @param string $query      Filter clicks over a search query.
     * @param string $pageSize   The number of results per page.
     * @param array  $filters    Analytics filters
     *
     * @return array
     */
    public function getTopClicksAnalytics($engineName, $query = null, $pageSize = null, $filters = null)
    {
        $params = [
            'engine_name' => $engineName,
            'query' => $query,
            'page.size' => $pageSize,
            'filters' => $filters,
        ];

        $endpoint = $this->getEndpoint('GetTopClicksAnalytics');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Returns queries anlaytics by usage count.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/analytics/queries
     *
     * @param string $engineName Name of the engine.
     * @param string $pageSize   The number of results per page.
     * @param array  $filters    Analytics filters
     *
     * @return array
     */
    public function getTopQueriesAnalytics($engineName, $pageSize = null, $filters = null)
    {
        $params = [
            'engine_name' => $engineName,
            'page.size' => $pageSize,
            'filters' => $filters,
        ];

        $endpoint = $this->getEndpoint('GetTopQueriesAnalytics');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Create or update documents.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/documents#create
     *
     * @param string $engineName Name of the engine.
     * @param array  $documents  List of document to index.
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
     * Retrieve details for all keys.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/credentials#all
     *
     * @param string $currentPage The page to fetch. Defaults to 1.
     * @param string $pageSize    The number of results per page.
     *
     * @return array
     */
    public function listCredentials($currentPage = null, $pageSize = null)
    {
        $params = [
            'page.current' => $currentPage,
            'page.size' => $pageSize,
        ];

        $endpoint = $this->getEndpoint('ListCredentials');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Retrieve available curations for the engine.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/curations#read
     *
     * @param string $engineName  Name of the engine.
     * @param string $currentPage The page to fetch. Defaults to 1.
     * @param string $pageSize    The number of results per page.
     *
     * @return array
     */
    public function listCurations($engineName, $currentPage = null, $pageSize = null)
    {
        $params = [
            'engine_name' => $engineName,
            'page.current' => $currentPage,
            'page.size' => $pageSize,
        ];

        $endpoint = $this->getEndpoint('ListCurations');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * List all available documents with optional pagination support.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/documents#list
     *
     * @param string $engineName  Name of the engine.
     * @param string $currentPage The page to fetch. Defaults to 1.
     * @param string $pageSize    The number of results per page.
     *
     * @return array
     */
    public function listDocuments($engineName, $currentPage = null, $pageSize = null)
    {
        $params = [
            'engine_name' => $engineName,
            'page.current' => $currentPage,
            'page.size' => $pageSize,
        ];

        $endpoint = $this->getEndpoint('ListDocuments');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Retrieves all engines with optional pagination support.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/engines#list
     *
     * @param string $currentPage The page to fetch. Defaults to 1.
     * @param string $pageSize    The number of results per page.
     *
     * @return array
     */
    public function listEngines($currentPage = null, $pageSize = null)
    {
        $params = [
            'page.current' => $currentPage,
            'page.size' => $pageSize,
        ];

        $endpoint = $this->getEndpoint('ListEngines');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Retrieve available synonym sets for the engine.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/synonyms#get
     *
     * @param string $engineName  Name of the engine.
     * @param string $currentPage The page to fetch. Defaults to 1.
     * @param string $pageSize    The number of results per page.
     *
     * @return array
     */
    public function listSynonymSets($engineName, $currentPage = null, $pageSize = null)
    {
        $params = [
            'engine_name' => $engineName,
            'page.current' => $currentPage,
            'page.size' => $pageSize,
        ];

        $endpoint = $this->getEndpoint('ListSynonymSets');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Send data about clicked results.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/clickthrough
     *
     * @param string $engineName Name of the engine.
     * @param string $queryText  Search query text.
     * @param string $documentId The id of the document that was clicked on.
     * @param string $requestId  The request id returned in the meta tag of a search API response.
     * @param array  $tags       Array of strings representing additional information you wish to track with the clickthrough.
     *
     * @return array
     */
    public function logClickthrough($engineName, $queryText, $documentId, $requestId = null, $tags = null)
    {
        $params = [
            'engine_name' => $engineName,
            'query' => $queryText,
            'document_id' => $documentId,
            'request_id' => $requestId,
            'tags' => $tags,
        ];

        $endpoint = $this->getEndpoint('LogClickthrough');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Run several search in the same request.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/search#multi
     *
     * @param string $engineName Name of the engine.
     * @param array  $queries    Search queries.
     *
     * @return array
     */
    public function multiSearch($engineName, $queries)
    {
        $params = [
            'engine_name' => $engineName,
            'queries' => $queries,
        ];

        $endpoint = $this->getEndpoint('MultiSearch');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Provide relevant query suggestions for incomplete queries.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/query-suggestion
     *
     * @param string $engineName Name of the engine.
     * @param string $query      A partial query for which to receive suggestions.
     * @param array  $fields     List of fields to use to generate suggestions. Defaults to all text fields.
     * @param int    $size       Number of query suggestions to return. Must be between 1 and 20. Defaults to 5.
     *
     * @return array
     */
    public function querySuggestion($engineName, $query, $fields = null, $size = null)
    {
        $params = [
            'engine_name' => $engineName,
            'query' => $query,
            'types.documents.fields' => $fields,
            'size' => $size,
        ];

        $endpoint = $this->getEndpoint('QuerySuggestion');
        $endpoint->setParams($params);

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
     * @param string $engineName          Name of the engine.
     * @param string $queryText           Search query text.
     * @param array  $searchRequestParams Search request parameters.
     *
     * @return array
     */
    public function search($engineName, $queryText, $searchRequestParams = null)
    {
        $params = [
            'engine_name' => $engineName,
            'query' => $queryText,
        ];

        $endpoint = $this->getEndpoint('Search');
        $endpoint->setParams($params);
        $endpoint->setBody($searchRequestParams);

        return $this->performRequest($endpoint);
    }

    /**
     * Update an existing API key.
     *
     * You are unable to change the &#39;type&#39; of a key.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/credentials#update
     *
     * @param string $keyName          API key name.
     * @param string $name             API key name.
     * @param bool   $read             Should this key have read access? Only accepted if &#39;type&#39; is &#39;private&#39;.
     * @param bool   $write            Should this key have write access? Only accepted if &#39;type&#39; is &#39;private&#39;.
     * @param bool   $accessAllEngines Should this key have access to all present and future engines? If true, include an &#39;engines&#39; param.
     * @param array  $engines          If access_all_engines is set to false, provide the engines that the key is permitted to access.
     *
     * @return array
     */
    public function updateCredential($keyName, $name, $read, $write, $accessAllEngines = false, $engines = null)
    {
        $params = [
            'key_name' => $keyName,
            'name' => $name,
            'read' => $read,
            'write' => $write,
            'access_all_engines' => $accessAllEngines,
            'engines' => $engines,
        ];

        $endpoint = $this->getEndpoint('UpdateCredential');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Update an existing curation.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/curations#update
     *
     * @param string $engineName     Name of the engine.
     * @param string $curationId     Curation id.
     * @param array  $queries        List of affected search queries.
     * @param array  $promotedDocIds List of promoted document ids.
     * @param array  $hiddenDocIds   List of hidden document ids.
     *
     * @return array
     */
    public function updateCuration($engineName, $curationId, $queries, $promotedDocIds = null, $hiddenDocIds = null)
    {
        $params = [
            'engine_name' => $engineName,
            'curation_id' => $curationId,
            'queries' => $queries,
            'promoted' => $promotedDocIds,
            'hidden' => $hiddenDocIds,
        ];

        $endpoint = $this->getEndpoint('UpdateCuration');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Partial update of documents.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/documents#partial
     *
     * @param string $engineName Name of the engine.
     * @param array  $documents  List of document to update.
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
