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
     * @var array credentials
     */
    private $createdCredentials = [];

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
     * Test Credentials API list keys endpoint.
     *
     * @covers \Elastic\AppSearch\Client\Client::listCredentials
     */
    public function testListCredentials()
    {
        $credentials = $this->getAdminClient()->listCredentials();
        $this->assertArrayHasKey('meta', $credentials);
        $this->assertArrayHasKey('results', $credentials);
        $this->assertNotEmpty($credentials['results']);
    }

    /**
     * Test Credentials API CRUD.
     *
     * @covers \Elastic\AppSearch\Client\Client::createCredential
     * @covers \Elastic\AppSearch\Client\Client::getCredential
     * @covers \Elastic\AppSearch\Client\Client::updateCredential
     * @covers \Elastic\AppSearch\Client\Client::deleteCredential
     *
     * @testWith ["public-", "search", null, null, true]
     *           ["private-", "private", true, false, true]
     *           ["admin-", "admin", null, null, null]
     */
    public function testCredentialCRUD($keyPrefix, $keyType, $read, $write, $accessAllEngines)
    {
        $client = $this->getAdminClient();
        $randomString = strtolower(substr(str_shuffle(md5(microtime())), 0, 24));
        $keyName = $keyPrefix . $randomString;
        $credential = $client->createCredential($keyName, $keyType, $read, $write, $accessAllEngines);
        $this->createdCredentials[$keyName] = $credential;
        $this->assertArrayHasKey('id', $credential);
        $this->assertEquals($keyType, $credential['type']);
        $this->assertEquals($keyName, $credential['name']);

        $retrieved = $client->getCredential($keyName);
        $this->assertArrayHasKey('id', $retrieved);
        $this->assertEquals($keyType, $retrieved['type']);
        $this->assertEquals($keyName, $retrieved['name']);
        $this->assertEquals($credential['key'], $retrieved['key']);

        $randomString = strtolower(substr(str_shuffle(md5(microtime())), 0, 24));
        $newName = $keyPrefix . $randomString;

        $updated = false;
        try {
            $updated = $client->updateCredential($keyName, $newName, $keyType, $read, $write, $accessAllEngines, null);
        } catch (\Exception $e) {
            if (!($e instanceof \Elastic\OpenApi\Codegen\Exception\BadRequestException)) {
                $this->assertEquals('Elastic\\OpenApi\\Codegen\\Exception\\BadRequestException', get_class($e));
            }
        }
        if ($updated) {
            $this->assertArrayHasKey('meta', $updated);
            $this->assertArrayHasKey('results', $updated);
            $this->assertNotEmpty($updated['results']);
            unset($this->createdCredentials[$keyName]);
            $this->createdCredentials[$newName] = $updated['results'][0];
            $keyName = $newName;
        }

        if (!is_null($read)) {
            $updatedRW = $client->updateCredential($keyName, $keyName, $keyType, !$read, !$write, true, null);
            $this->assertFalse($updatedRW['read'] === $read);
            $this->assertFalse($updatedRW['write'] === $write);
            $this->assertEquals($credential['key'], $updatedRW['key']);
            $this->assertEquals($credential['name'], $updatedRW['name']);
            $this->assertEquals($credential['type'], $updatedRW['type']);
        }

        $deleted = $client->deleteCredential($keyName);
        $this->assertArrayHasKey('deleted', $deleted);
        $this->assertTrue($deleted['deleted']);
        unset($this->createdCredentials[$keyName]);
    }

    /**
     * Delete any keys that were created.
     */
    public function tearDown()
    {
        $client = $this->getAdminClient();
        if ($client) {
            foreach ($this->createdCredentials as $name => $keyData) {
                $client->deleteCredential($name);
            }
        }
    }
}
