<?php
/**
 * This file is part of the Elastic App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elastic\AppSearch\Client\Tests\Integration;

/**
 * Integration test for the Analytics API.
 *
 * @package Elastic\AppSearch\Client\Test\Integration
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache2
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

        $this->assertLessThanOrEqual($size, $topClicks['meta']['page']['size']);
        $this->assertArrayHasKey('results', $topClicks);
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

        $this->assertLessThanOrEqual($size, $queries['meta']['page']['size']);
        $this->assertArrayHasKey('results', $queries);
    }
}
