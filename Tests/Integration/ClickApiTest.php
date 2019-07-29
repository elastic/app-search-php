<?php
/**
 * This file is part of the Elastic App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elastic\AppSearch\Client\Tests\Integration;

/**
 * Integration test for the Clickthrough API.
 *
 * @package Elastic\AppSearch\Client\Test\Integration
 */
class ClickApiTest extends AbstractEngineTestCase
{
    /**
     * Test sending a click trough the API.
     */
    public function testSendClick()
    {
        $client = $this->getDefaultClient();
        $engineName = $this->getDefaultEngineName();

        $this->assertEmpty($client->logClickthrough($engineName, 'cat', 'INscMGmhmX4'));
    }
}
