<?php
/**
 * This file is part of the Swiftype App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swiftype\AppSearch\Tests\Integration;

use Swiftype\AppSearch\ClientBuilder;
use Swiftype\AppSearch\Client;

/**
 * Testing client instantiaton and error handling.
 *
 * @package Swiftype\AppSearch\Test\Integration
 *
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class ClientTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test instantiation of a client through the client builder.
     */
    public function testClientBuilder()
    {
        $client = ClientBuilder::create($_ENV['ST_API_ENDPOINT'], $_ENV['ST_API_KEY'])->build();
        $this->assertInstanceOf(Client::class, $client);
    }

    /**
     * Test exception throwned when providing an invalid API endpoint.
     *
     * @param string API Endpoint.
     * @param string Class of the exception that should be raised.
     *
     * @testWith ["http://localhost:5000", "\\Elastic\\OpenApi\\Codegen\\Exception\\CouldNotConnectToHostException"]
     *           ["http://foo.bar:5000", "\\Elastic\\OpenApi\\Codegen\\Exception\\CouldNotResolveHostException"]
     *           ["_foo_", "\\Elastic\\OpenApi\\Codegen\\Exception\\UnexpectedValueException"]
     */
    public function testConnectionErrors($apiEndpoint, $exceptionClass)
    {
        $this->expectException($exceptionClass);
        $client = ClientBuilder::create($apiEndpoint, $_ENV['ST_API_KEY'])->build();
        $client->listEngines();
    }

    /**
     * Test an Authentication exception is thrown when providing an in valid API Key.
     *
     * @expectedException \Elastic\OpenApi\Codegen\Exception\AuthenticationException
     */
    public function testAuthenticationError()
    {
        $client = ClientBuilder::create($_ENV['ST_API_ENDPOINT'], 'not-an-api-key')->build();
        $client->listEngines();
    }
}
