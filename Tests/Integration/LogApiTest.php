<?php
/**
 * This file is part of the Swiftype App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swiftype\AppSearch\Tests\Integration;

/**
 * Integration test for the Log API.
 *
 * @package Swiftype\AppSearch\Test\Integration
 *
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class LogApiTest extends AbstractEngineTestCase
{
    /**
     * Test the basic API log enpoint is returning results.
     *
     * @testWith [null, null, null, null, null, null]
     *           ["search", null, null, null, null, null]
     *           ["search", 2, 2, null, null, null]
     *           [null, null, null, 200, null, null]
     *           [null, null, null, 400, null, null]
     *           [null, null, null, null, "POST", null]
     *           [null, null, null, null, null, null, "asc"]
     *           [null, null, null, null, null, null, "desc"]
     *           [null, null, null, null, null, null, "ASC"]
     *           [null, null, null, null, null, null, "DESC"]
     *           ["search", 2, 2, 200, "GET", "desc"]
     */
    public function testGetLogs($query, $currentPage, $pageSize, $status, $method, $sortDir)
    {
        $client = $this->getDefaultClient();
        $engine = $this->getDefaultEngineName();

        $fromDate = date('c', strtotime('yesterday'));
        $toDate = date('c');

        $logs = $client->getApiLogs($engine, $fromDate, $toDate, $currentPage, $pageSize, $query, $status, $method, $sortDir);

        $this->assertNotEmpty($logs['results']);

        if ($pageSize) {
            $this->assertEquals($logs['meta']['page']['current'], $currentPage ? $currentPage : 1);
            $this->assertEquals($logs['meta']['page']['size'], $pageSize);
        }

        if ($query) {
            $this->assertEquals($query, $logs['meta']['query']);
        }

        if ($status) {
            $this->assertEquals($status, $logs['meta']['filters']['status']);
        }

        if ($method) {
            $this->assertEquals($method, $logs['meta']['filters']['method']);
        }

        if ($sortDir) {
            $this->assertEquals($sortDir, $logs['meta']['sort_direction']);
        }
    }
}
