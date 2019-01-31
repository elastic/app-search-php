<?php
/**
 * This file is part of the Swiftype App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swiftype\AppSearch\Tests\Integration;

use Swiftype\AppSearch\Tests\Integration\Helper\SampleDocuments;

/**
 * Integration test for the Search API.
 *
 * @package Swiftype\AppSearch\Test\Integration
 *
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class SearchApiTest extends AbstractTestCase
{
    /**
     * Import the documents into the default engine before running tests.
     */
    public function setUp()
    {
        parent::setUp();

        $documents = (new SampleDocuments())->getDocuments();
        self::$defaultClient->indexDocuments(self::$defaultEngine, $documents);

        do {
            // We wait for the doc to be searchable before launching the test.
            $searchResponse = self::$defaultClient->search(self::$defaultEngine, ['query' => '']);
            $searchableDocs = $searchResponse['meta']['page']['total_results'];
        } while ($searchableDocs == 0);
    }

    /**
     * Run simple searches with optional pagination and check result returned.
     *
     * @param array $searchRequest The search request.
     *
     * @testWith [{"query" : "cat", "page": {"current": 1, "size": 10}}]
     *           [{"query" : "cat", "page": {"current": 1, "size": 1}}]
     *           [{"query" : "", "page": {"current": 1, "size": 10}}]
     *           [{"query" : "original", "page": {"current": 1, "size": 10}}]
     *           [{"query" : "notfoundable", "page": {"current": 1, "size": 10}}]
     */
    public function testSimpleSearch($searchRequest)
    {
        $searchResponse = self::$defaultClient->search(self::$defaultEngine, $searchRequest);

        $this->assertArrayHasKey('meta', $searchResponse);
        $this->assertArrayHasKey('results', $searchResponse);
        $this->assertArrayHasKey('page', $searchResponse['meta']);
        $this->assertNotEmpty($searchResponse['meta']['request_id']);

        if (isset($searchRequest['page']['size'])) {
            $this->assertEquals($searchRequest['page']['size'], $searchResponse['meta']['page']['size']);
            $this->assertEquals($searchRequest['page']['current'], $searchResponse['meta']['page']['current']);
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
     * @param array   $filters              Search filters.
     * @param integer $expectedResultsCount Number of expected results in the sample data.
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
        $searchRequest = ['query' => '', 'filters' => $filters];
        $searchResponse = self::$defaultClient->search(self::$defaultEngine, $searchRequest);
        $this->assertCount($expectedResultsCount, $searchResponse['results']);
    }

    /**
     * Run simple facets searches and check the number of results.
     *
     * @param array   $facets             Search Facets.
     * @param integer $expectedValueCount Number of values expected in the facet.
     *
     * @testWith [{"tags": {"type": "value"}}, 5]
     *           [{"tags": [{"type": "value", "size": 3, "sort": {"value": "asc"}}]}, 3]
     */
    public function testFacetedSearch($facets, $expectedValueCount)
    {
        $searchRequest = ['query' => '', 'facets' => $facets];
        $searchResponse = self::$defaultClient->search(self::$defaultEngine, $searchRequest);
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
}
