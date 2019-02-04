<?php
/**
 * This file is part of the Swiftype App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swiftype\AppSearch\Tests\Integration;

/**
 * Integration test for the Synonyms API.
 *
 * @package Swiftype\AppSearch\Test\Integration
 *
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class SynonymApiTest extends AbstractEngineTestCase
{
    /**
     * @var boolean
     */
    protected static $importSampleDocs = false;

    /**
     * Test Synonym API endpoints (create, get, list, delete).
     *
     * @param array $synonymSetData
     *
     * @testWith [{"synonyms": ["foo", "bar"]}]
     */
    public function testSynonymsApi($synonymSetData)
    {
        $client = $this->getDefaultClient();
        $engineName = $this->getDefaultEngineName();

        $synonymSet = $client->createSynonymSet($engineName, $synonymSetData);
        $this->assertArrayHasKey('id', $synonymSet);

        $synonymSet = $client->getSynonymSet($engineName, $synonymSet['id']);
        $this->assertEquals($synonymSetData['synonyms'], $synonymSet['synonyms']);

        $synonymSetListResponse = $client->listSynonymSets($engineName);
        $this->assertEquals(1, $synonymSetListResponse['meta']['page']['total_results']);
        $this->assertCount(1, $synonymSetListResponse['results']);

        $deleteOperationResponse = $client->deleteSynonymSet($engineName, $synonymSet['id']);
        $this->assertEquals(['deleted' => true], $deleteOperationResponse);
    }
}
