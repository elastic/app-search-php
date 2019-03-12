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
     */
    public function testGetLogs()
    {
        $client = $this->getDefaultClient();
        $engine = $this->getDefaultEngineName();

        $fromDate = date('c', strtotime('yesterday'));
        $toDate = date('c');

        $logs = $client->getApiLogs($engine, $fromDate, $toDate);

        $this->assertNotEmpty($logs['results']);
    }
}
