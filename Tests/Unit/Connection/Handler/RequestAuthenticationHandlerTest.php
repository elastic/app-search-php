<?php
/**
 * This file is part of the Swiftype App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swiftype\AppSearch\Tests\Unit\Connection\Handler;

use PHPUnit\Framework\TestCase;
use Swiftype\AppSearch\Connection\Handler\RequestAuthenticationHandler;

/**
 * Unit tests for the authentication handler.
 *
 * @package Swiftype\AppSearch\Test\Unit\Connection\Handler
 *
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
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
