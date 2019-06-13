<?php
/**
 * This file is part of the Elastic App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elastic\AppSearch\Client\Tests\Integration;

/**
 * Integration test for the Curation API.
 *
 * @package Elastic\AppSearch\Client\Test\Integration
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache2
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class CurationApiTest extends AbstractEngineTestCase
{
    /**
     * @var bool
     */
    protected static $importSampleDocs = true;

    /**
     * Test Curation API endpoints (create, get, list, update and delete).
     *
     * @param array $curationData
     *
     * @testWith [[""], ["INscMGmhmX4"], ["JNDFojsd02"]]
     *           [["cat", "grumpy"], ["INscMGmhmX4"], null]
     *           [["lol"], null, ["INscMGmhmX4"]]
     */
    public function testCurationApi($queries, $promotedIds, $hiddenIds)
    {
        $client = $this->getDefaultClient();
        $engineName = $this->getDefaultEngineName();

        $curation = $client->createCuration($engineName, $queries, $promotedIds, $hiddenIds);
        $this->assertArrayHasKey('id', $curation);

        $curation = $client->getCuration($engineName, $curation['id']);
        $this->assertEquals($queries, $curation['queries']);

        $curationListResponse = $client->listCurations($engineName);
        $this->assertEquals(1, $curationListResponse['meta']['page']['total_results']);
        $this->assertCount(1, $curationListResponse['results']);

        $updateResponse = $client->updateCuration($engineName, $curation['id'], $queries, $promotedIds, $hiddenIds);
        $this->assertArrayHasKey('id', $updateResponse);
        $this->assertEquals($curation['id'], $updateResponse['id']);

        $deleteOperationResponse = $client->deleteCuration($engineName, $curation['id']);
        $this->assertEquals(['deleted' => true], $deleteOperationResponse);
    }
}
