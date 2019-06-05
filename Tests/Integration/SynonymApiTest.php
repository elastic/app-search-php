<?php
/**
 * This file is part of the Elastic App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elastic\AppSearch\Client\Tests\Integration;

/**
 * Integration test for the Synonyms API.
 *
 * @package Elastic\AppSearch\Client\Test\Integration
 *
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class SynonymApiTest extends AbstractEngineTestCase
{
    /**
     * Test Synonym API endpoints (create, get, list, delete).
     *
     * @param array $synonymSetData
     *
     * @testWith [["foo", "bar"]]
     */
    public function testSynonymsApi($synonyms)
    {
        $client = $this->getDefaultClient();
        $engineName = $this->getDefaultEngineName();

        $synonymSet = $client->createSynonymSet($engineName, $synonyms);
        $this->assertArrayHasKey('id', $synonymSet);

        $synonymSet = $client->getSynonymSet($engineName, $synonymSet['id']);
        $this->assertEquals($synonyms, $synonymSet['synonyms']);

        $synonymSetListResponse = $client->listSynonymSets($engineName);
        $this->assertEquals(1, $synonymSetListResponse['meta']['page']['total_results']);
        $this->assertCount(1, $synonymSetListResponse['results']);

        $deleteOperationResponse = $client->deleteSynonymSet($engineName, $synonymSet['id']);
        $this->assertEquals(['deleted' => true], $deleteOperationResponse);
    }
}
