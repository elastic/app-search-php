<?php
/**
 * This file is part of the Elastic App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elastic\AppSearch\Client\Tests\Integration;

use Elastic\AppSearch\Client\ClientBuilder;

/**
 * Integration test for the Credentials API.
 *
 * @package Elastic\AppSearch\Client\Test\Integration
 */
class CredentialsApiTest extends AbstractClientTestCase
{
    /**
     * @var \Elastic\AppSearch\Client\Client
     */
    private static $adminClient;

    /**
     * Init a default client to run all the tests.
     */
    public static function setupBeforeClass()
    {
        $clientBuilder = new ClientBuilder();
        self::$adminClient = $clientBuilder->create(getenv('AS_URL'), getenv('AS_ADMIN_KEY'))->build();
    }

    /**
     * @return \Elastic\AppSearch\Client\Client
     */
    protected static function getAdminClient()
    {
        return self::$adminClient;
    }

    /**
     * Test Credentials API endpoints (create, get, list, update and delete).
     *
     * @param array $credentialsData
     */
    public function testCredentialsApi()
    {
        $client = $this->getAdminClient();

        $credentials = $client->listCredentials();
        $this->assertArrayHasKey('meta', $credentials);
        $this->assertArrayHasKey('results', $credentials);
        $this->assertNotEmpty($credentials['results']);
    }

}
