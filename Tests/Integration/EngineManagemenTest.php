<?php
/**
 * This file is part of the Swiftype App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swiftype\AppSearch\Tests\Integration;

use PHPUnit\Framework\TestCase;
use Swiftype\AppSearch\Client;
use Swiftype\AppSearch\ClientBuilder;

/**
 * A basic test of the index management.
 *
 * @todo : replace with better implementation.
 *
 * @package Swiftype\AppSearch\Test\Integration
 *
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class EngineManagementTest extends TestCase
{
    private $engineName = 'test-engine';
    private $docSamples = [
        ['id' => '678', 'name' => 'an indexed doc'],
        ['id' => '671', 'name' => 'another doc'],
    ];

    public function setUp()
    {
        $config = new Config();
        $this->apiEndpoint = $config->getApiEndpoint();
        $this->apiKey = $config->getApiKey();
    }

    public function testClient()
    {
        $client = ClientBuilder::create($this->apiEndpoint, $this->apiKey);
        $this->assertInstanceOf(Client::class, $client);

        $this->assertEquals($this->engineName, $client->createEngine(['name' => $this->engineName])['name']);

        $engine = $client->getEngine($this->engineName);
        $this->assertEquals($this->engineName, $engine['name']);
        $this->assertNull($engine['language']);

        $engineList = $client->listEngines(['page' => ['current' => 1, 'size' => 20]]);
        $this->assertContains($engine, $engineList['results']);

        $this->assertTrue($client->deleteEngine($this->engineName)['deleted']);
    }
}
