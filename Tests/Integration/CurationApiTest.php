<?php
/**
 * This file is part of the Swiftype App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swiftype\AppSearch\Tests\Integration;

/**
 * Integration test for the Curation API.
 *
 * @package Swiftype\AppSearch\Test\Integration
 *
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
     * @testWith [{"queries": [""], "promoted": ["INscMGmhmX4"], "hidden": ["JNDFojsd02"]}]
     */
    public function testCurationApi($curationData)
    {
        $client = $this->getDefaultClient();
        $engineName = $this->getDefaultEngineName();

        $curation = $client->createCuration($engineName, $curationData);
        $this->assertArrayHasKey('id', $curation);

        $curation = $client->getCuration($engineName, $curation['id']);
        $this->assertEquals($curationData['promoted'], $curation['promoted']);
        $this->assertEquals($curationData['hidden'], $curation['hidden']);
        $this->assertEquals($curationData['queries'], $curation['queries']);

        $curationListResponse = $client->listCurations($engineName);
        $this->assertEquals(1, $curationListResponse['meta']['page']['total_results']);
        $this->assertCount(1, $curationListResponse['results']);

        unset($curationData['hidden']);
        $updateCurationResponse = $client->updateCuration($engineName, $curation['id'], $curationData);
        $this->assertArrayHasKey('id', $updateCurationResponse);
        $this->assertEquals($curation['id'], $updateCurationResponse['id']);

        $deleteOperationResponse = $client->deleteCuration($engineName, $curation['id']);
        $this->assertEquals(['deleted' => true], $deleteOperationResponse);
    }
}
