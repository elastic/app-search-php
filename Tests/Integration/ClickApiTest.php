<?php
/**
 * This file is part of the Swiftype App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swiftype\AppSearch\Tests\Integration;

/**
 * Integration test for the Clickthrough API.
 *
 * @package Swiftype\AppSearch\Test\Integration
 *
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class ClickApiTest extends AbstractEngineTestCase
{
    /**
     * @var boolean
     */
    protected static $importSampleDocs = true;

    /**
     * Test sending a click trough the API.
     */
    public function testSendClick()
    {
        $client = $this->getDefaultClient();
        $engineName = $this->getDefaultEngineName();

        $this->assertEmpty($client->sendClick($engineName, ['query' => 'cat', 'document_id' => '5678']));
    }
}
