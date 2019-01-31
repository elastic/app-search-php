<?php
/**
 * This file is part of the Swiftype App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swiftype\AppSearch\Tests\Integration;

/**
 * Integrations test for the Engine API.
 *
 * @package Swiftype\AppSearch\Test\Integration
 *
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class EngineApiTest extends AbstractTestCase
{
    public function setUp()
    {
        sleep(2);
        // Skip automatic engine creation.
    }

    /**
     * This test run the following scenario:
     * - Create a new engine and check the name in the return.
     * - Retrieve this engine and test name and the language.
     * - Try to list the engines and check the new engine is present in the entries.
     * - Delete the engine and check the result.
     *
     * @dataProvider getLanguages
     */
    public function testApiMethods($language)
    {
        $client = self::$defaultClient;

        $engineData = ['name' => self::$defaultEngine, 'language' => $language];
        $this->assertEquals(self::$defaultEngine, $client->createEngine($engineData)['name']);

        $engine = $client->getEngine(self::$defaultEngine);
        $this->assertEquals(self::$defaultEngine, $engine['name']);
        $this->assertEquals($language, $engine['language']);

        $engineList = $client->listEngines(['page' => ['current' => 1, 'size' => 20]]);
        $this->assertContains($engine, $engineList['results']);

        $this->assertTrue($client->deleteEngine(self::$defaultEngine)['deleted']);
    }

    /**
     * Try to get a non existing engine.
     *
     * @expectedException \Swiftype\AppSearch\Exception\NotFoundException
     */
    public function testGetNonExistingEngine()
    {
        self::$defaultClient->getEngine('some-non-existing-engine');
    }

    /**
     * Try to delete a non existing engine.
     *
     * @expectedException \Swiftype\AppSearch\Exception\NotFoundException
     */
    public function testDeleteNonExistingEngine()
    {
        self::$defaultClient->getEngine('some-non-existing-engine');
    }

    /**
     * Try to delete a non existing engine.
     *
     * @expectedException \Swiftype\AppSearch\Exception\BadRequestException
     */
    public function testCreateAlreadyExistingEngine()
    {
        self::$defaultClient->createEngine(['name' => self::$defaultEngine]);
        self::$defaultClient->createEngine(['name' => self::$defaultEngine]);
    }

    /**
     * Try to create engine with invalid params.
     *
     * @dataProvider getInvalidCreateEngineRequest
     *
     * @expectedException \Swiftype\AppSearch\Exception\BadRequestException
     */
    public function testCreateEngineFromInvalidParams($engineData)
    {
        self::$defaultClient->createEngine($engineData);
    }

    /**
     * List of languages used in test.
     *
     * @return array
     */
    public function getLanguages()
    {
        return [['en'], [null]];
    }

    public function getInvalidCreateEngineRequest()
    {
        return [
            [[]],
            [['name' => 'Default Engine']],
            [['name' => self::$defaultEngine, 'langugage' => 'ca']],
            [['name' => self::$defaultEngine, 'foo' => 'bar']],
        ];
    }
}
