<?php
/**
 * This file is part of the Elastic App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elastic\AppSearch\Client\Tests\Unit\Connection\Handler;

use PHPUnit\Framework\TestCase;
use Elastic\AppSearch\Client\Client;
use Elastic\AppSearch\Client\ClientBuilder;

/**
 * Check the client builder is able to instantiate new clients.
 *
 * @package Elastic\AppSearch\Client\Test\Unit\Connection\Handler
 */
class ClientBuilderTest extends TestCase
{
    /**
     * Check instantiation with valid endpoints.
     *
     * @dataProvider validApiEndpoints
     *
     * @param string $apiEndpoint
     */
    public function testInstantiation($apiEndpoint)
    {
        $client = ClientBuilder::create($apiEndpoint, 'apiKey')->build();
        $this->assertInstanceOf(Client::class, $client);
    }

    /**
     * Check instantiation with valid endpoints.
     *
     * @dataProvider invalidApiEndpoints
     *
     * @expectedException \Elastic\OpenApi\Codegen\Exception\UnexpectedValueException
     *
     * @param string $apiEndpoint
     */
    public function testInvalidEndpoints($apiEndpoint)
    {
        ClientBuilder::create($apiEndpoint, 'apiKey')->build();
    }

    /**
     * @return array
     */
    public function validApiEndpoints()
    {
        return [['https://test.com'], ['http://test.com'], ['http://test'], ['https://test'], ['test.com'], ['test']];
    }

    /**
     * @return array
     */
    public function invalidApiEndpoints()
    {
        return [['test_']];
    }
}
