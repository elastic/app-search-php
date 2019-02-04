<?php
/**
 * This file is part of the Swiftype App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swiftype\AppSearch\Tests\Integration;

use Swiftype\Exception\NotFoundException;

/**
 * Integrations test for the Engine API.
 *
 * @package Swiftype\AppSearch\Test\Integration
 *
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class EngineApiTest extends AbstractClientTestCase
{
    /**
     * @var array
     */
    private $engines = [];

    /**
     * Delete the engines created during the test.
     */
    public function tearDown()
    {
        foreach ($this->engines as $engineName) {
            try {
                $this->getDefaultClient()->deleteEngine($engineName);
                var_dump($engineName);
            } catch (NotFoundException $e) {
                // The engine have already been deleted. Nothing to do.
            }
        }
        $this->engines = [];
    }

    /**
     * This test run the following scenario:
     * - Create a new engine and check the name in the return.
     * - Retrieve this engine and test name and the language.
     * - Try to list the engines and check the new engine is present in the entries.
     * - Delete the engine and check the result.
     *
     * @param string $language Engine language.
     *
     * @testWith ["en"]
     *           [null]
     */
    public function testApiMethods($language)
    {
        $client = $this->getDefaultClient();
        $engineName = $this->getEngineName(__METHOD__, func_get_args());

        $engineData = ['name' => $engineName, 'language' => $language];
        $this->assertEquals($engineName, $client->createEngine($engineData)['name']);
        $this->engines[] = $engineName;

        $engine = $client->getEngine($engineName);
        $this->assertEquals($engineName, $engine['name']);
        $this->assertEquals($language, $engine['language']);

        $engineList = $client->listEngines(['page' => ['current' => 1, 'size' => 20]]);
        $this->assertContains($engine, $engineList['results']);

        $this->assertTrue($client->deleteEngine($engineName)['deleted']);
    }

    /**
     * Try to get a non existing engine.
     *
     * @expectedException \Swiftype\Exception\NotFoundException
     */
    public function testGetNonExistingEngine()
    {
        $this->getDefaultClient()->getEngine('some-non-existing-engine');
    }

    /**
     * Try to delete a non existing engine.
     *
     * @expectedException \Swiftype\Exception\NotFoundException
     */
    public function testDeleteNonExistingEngine()
    {
        $this->getDefaultClient()->getEngine('some-non-existing-engine');
    }

    /**
     * Try to create an already existing engine.
     *
     * @expectedException \Swiftype\Exception\BadRequestException
     */
    public function testCreateAlreadyExistingEngine()
    {
        $engineName = $this->getEngineName(__METHOD__);
        $this->engines[] = $engineName;

        $this->getDefaultClient()->createEngine(['name' => $engineName]);
        $this->getDefaultClient()->createEngine(['name' => $engineName]);
    }

    private function getEngineName($method, $params = [])
    {
        $nameParts = [$this->getDefaultEngineName()];

        $methodParts = explode(':', $method);
        $nameParts[] = strtolower(end($methodParts));

        $nameParts = array_merge($nameParts, array_filter($params));

        return implode('-', $nameParts);
    }
}
