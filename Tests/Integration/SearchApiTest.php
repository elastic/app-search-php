<?php
/**
 * This file is part of the Elastic App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elastic\AppSearch\Client\Tests\Integration;

/**
 * Integration test for the Search API.
 *
 * @package Elastic\AppSearch\Client\Test\Integration
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache2
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class SearchApiTest extends AbstractEngineTestCase
{
    /**
     * @var bool
     */
    protected static $importSampleDocs = true;

    /**
     * Run simple searches with optional pagination and check result returned.
     *
     * @param array $searchRequest The search request.
     *
     * @testWith ["cat", {"page": {"current": 1, "size": 10}}]
     *           ["cat", {"page": {"current": 1, "size": 1}}]
     *           ["", {"page": {"current": 1, "size": 10}}]
     *           ["original", {"page": {"current": 1, "size": 10}}]
     *           ["notfoundable", {"page": {"current": 1, "size": 10}}]
     */
    public function testSimpleSearch($queryText, $searchParams)
    {
        $searchResponse = $this->getDefaultClient()->search($this->getDefaultEngineName(), $queryText, $searchParams);

        $this->assertArrayHasKey('meta', $searchResponse);
        $this->assertArrayHasKey('results', $searchResponse);
        $this->assertArrayHasKey('page', $searchResponse['meta']);
        $this->assertNotEmpty($searchResponse['meta']['request_id']);

        if (isset($searchParams['page']['size'])) {
            $this->assertEquals($searchParams['page']['size'], $searchResponse['meta']['page']['size']);
            $this->assertEquals($searchParams['page']['current'], $searchResponse['meta']['page']['current']);
        }

        $expectedResultCount = min(
            $searchResponse['meta']['page']['total_results'],
            $searchResponse['meta']['page']['size']
        );

        $this->assertCount($expectedResultCount, $searchResponse['results']);

        if ($expectedResultCount > 0) {
            $firstDoc = current($searchResponse['results']);
            $this->assertArrayHasKey('_meta', $firstDoc);
            $this->assertArrayHasKey('score', $firstDoc['_meta']);
        }
    }

    /**
     * Run simple filtered searches and check the number of results.
     *
     * @param array $filters              Search filters.
     * @param int   $expectedResultsCount Number of expected results in the sample data.
     *
     * @testWith [{"tags": ["Cats"]}, 2]
     *           [{"tags": ["Copycat"]}, 1]
     *           [{"tags": ["Copycat", "Hall Of Fame"]}, 2]
     *           [{"any": [{"tags": ["Copycat"]}, {"tags": "Hall Of Fame"}]}, 2]
     *           [{"all": [{"tags": ["Copycat"]}, {"tags": "Hall Of Fame"}]}, 0]
     *           [{"all": [{"tags": ["Cats"]}], "none": [{"tags": "Hall Of Fame"}]}, 1]
     */
    public function testFilteredSearch($filters, $expectedResultsCount)
    {
        $searchParams = ['filters' => $filters];
        $searchResponse = $this->getDefaultClient()->search($this->getDefaultEngineName(), '', $searchParams);
        $this->assertCount($expectedResultsCount, $searchResponse['results']);
    }

    /**
     * Run simple facets searches and check the number of results.
     *
     * @param array $facets             Search Facets.
     * @param int   $expectedValueCount Number of values expected in the facet.
     *
     * @testWith [{"tags": {"type": "value"}}, 5]
     *           [{"tags": [{"type": "value", "size": 3, "sort": {"value": "asc"}}]}, 3]
     */
    public function testFacetedSearch($facets, $expectedValueCount)
    {
        $searchParams = ['facets' => $facets];
        $searchResponse = $this->getDefaultClient()->search($this->getDefaultEngineName(), '', $searchParams);
        $this->assertArrayHasKey('facets', $searchResponse);

        foreach ($facets as $facetName => $facetDefinition) {
            if (!isset($facetDefinition['type'])) {
                $facetDefinition = current($facetDefinition);
            }

            $this->assertArrayHasKey($facetName, $searchResponse['facets']);
            $currentFacet = current($searchResponse['facets'][$facetName]);
            $this->assertEquals($facetDefinition['type'], $currentFacet['type']);
            $this->assertCount($expectedValueCount, $currentFacet['data']);
        }
    }

    /**
     * Run simple sorted searches against sample data and check the first result.
     *
     * @param array  $sortOrder          Sort order definition.
     * @param string $expectedFirstDocId Id of the first expected match.
     *
     * @testWith [{"title": "asc"}, "JNDFojsd02"]
     *           [{"title": "desc"}, "INscMGmhmX4"]
     *           [[{"title": "asc"}], "JNDFojsd02"]
     *           [[{"title": "desc"}], "INscMGmhmX4"]
     *           [[{"title": "asc"}, {"_score": "desc"}], "JNDFojsd02"]
     */
    public function testSortedSearch($sortOrder, $expectedFirstDocId)
    {
        $searchParams = ['sort' => $sortOrder];
        $searchResponse = $this->getDefaultClient()->search($this->getDefaultEngineName(), '', $searchParams);
        $this->assertEquals($expectedFirstDocId, $searchResponse['results'][0]['id']['raw']);
    }

    /**
     * Run simple searches against sample data using search fields and check the number of results.
     *
     * @param string $queryText            Search query text.
     * @param array  $searchFields         Search fields.
     * @param int    $expectedResultsCount Number of expected results in the sample data.
     *
     * @testWith ["cat", {"title": {}}, 2]
     *           ["cat", {"title": {"weight": 1}}, 2]
     *           ["cat", {"text" : {}}, 0]
     *           ["cat", {"title": {"weight": 1}, "text": {}}, 2]
     */
    public function testSearchFields($queryText, $searchFields, $expectedResultsCount)
    {
        $searchParams = ['search_fields' => $searchFields];
        $searchResponse = $this->getDefaultClient()->search($this->getDefaultEngineName(), $queryText, $searchParams);
        $this->assertCount($expectedResultsCount, $searchResponse['results']);
    }

    /**
     * Run a multisearch and check the content of the response.
     */
    public function testMultiSearch()
    {
        $queries = [['query' => ''], ['query' => 'cat']];

        $searchResponses = $this->getDefaultClient()->multiSearch($this->getDefaultEngineName(), $queries);

        $this->assertCount(count($queries), $searchResponses);

        foreach ($searchResponses as $searchResponse) {
            $this->assertArrayHasKey('meta', $searchResponse);
            $this->assertArrayHasKey('results', $searchResponse);
        }
    }
}
