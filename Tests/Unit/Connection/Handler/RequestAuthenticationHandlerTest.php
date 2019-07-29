<?php
/**
 * This file is part of the Elastic App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elastic\AppSearch\Client\Tests\Unit\Connection\Handler;

use PHPUnit\Framework\TestCase;
use Elastic\AppSearch\Client\Connection\Handler\RequestAuthenticationHandler;

/**
 * Unit tests for the authentication handler.
 *
 * @package Elastic\AppSearch\Client\Test\Unit\Connection\Handler
 */
class RequestAuthenticationHandlerTest extends TestCase
{
    /**
     * Check the auth header is present and contains the right value.
     */
    public function testAddAuthHeader()
    {
        $handler = function ($request) {
            return $request['headers'];
        };

        $authenticationHandler = new RequestAuthenticationHandler($handler, 'apiKey');
        $response = $authenticationHandler([]);
        $this->assertArrayHasKey('Authorization', $response);
        $this->assertEquals('Bearer apiKey', current($response['Authorization']));
    }
}
