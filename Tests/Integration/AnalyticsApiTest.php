<?php
/**
 * This file is part of the Swiftype App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swiftype\AppSearch\Tests\Integration;

/**
 * Integration test for the Analytics API.
 *
 * @package Swiftype\AppSearch\Test\Integration
 *
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class AnalyticsApiTest extends AbstractEngineTestCase
{
    /**
     * Test top clicks analytics request.
     *
     * @testWith ["brunch", 3]
     *           ["brunch", 20]
     */
    public function testTopClicks($searchTerm, $size)
    {
        $client = $this->getDefaultClient();
        $engine = $this->getDefaultEngineName();

        $topClicks = $client->getTopClicksAnalytics($engine, $searchTerm, $size);

        $this->assertNotEmpty($topClicks['meta']['page']['size']);
        $this->assertLessThanOrEqual($size, $topClicks['meta']['page']['size']);

        $this->assertNotEmpty($topClicks['results']);
        $this->assertArrayHasKey('document_id', current($topClicks['results']));
        $this->assertArrayHasKey('clicks', current($topClicks['results']));
    }

    /**
     * Test top queries analytics request.
     *
     * @testWith [3, null, null]
     *           [3, true, true]
     *           [3, true, false]
     *           [20, false, true]
     *           [20, false, false]
     */
    public function testTopQueries($size, $withResults, $clicked)
    {
        $client = $this->getDefaultClient();
        $engine = $this->getDefaultEngineName();

        $filters = [];

        if (null != $withResults) {
            $filters['all'][] = ['results' => $withResults];
        }

        if (null != $clicked) {
            $filters['all'][] = ['clicks' => $clicked];
        }

        $queries = $client->getTopQueriesAnalytics($engine, $size, !empty($filters) ? $filters : null);

        $this->assertNotEmpty($queries['meta']['page']['size']);
        $this->assertLessThanOrEqual($size, $queries['meta']['page']['size']);

        $this->assertNotEmpty($queries['results']);
        $this->assertArrayHasKey('term', current($queries['results']));
        $this->assertArrayHasKey('queries', current($queries['results']));
        $this->assertArrayHasKey('clicks', current($queries['results']));
    }
}
