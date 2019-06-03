<?php
/**
 * This file is part of the Swiftype App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swiftype\AppSearch\Tests\Unit\Connection\Handler;

use PHPUnit\Framework\TestCase;
use Swiftype\AppSearch\Connection\Handler\RequestClientHeaderHandler;

/**
 * Unit tests for the client header handler.
 *
 * @package Swiftype\AppSearch\Test\Unit\Connection\Handler
 *
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class RequestClientHeaderHandlerTest extends TestCase
{
    /**
     * Check the reporting headers is present and contains the right value.
     */
    public function testAddAuthHeaderWithoutIntegrationName()
    {
        $handler = function ($request) {
            return $request['headers'];
        };

        $requestHandler = new RequestClientHeaderHandler($handler);
        $response = $requestHandler([]);

        $this->assertArrayHasKey('X-Swiftype-Client', $response);
        $this->assertArrayHasKey('X-Swiftype-Client-Version', $response);
    }

    /**
     * Check the reporting headers is present and contains the right value.
     */
    public function testAddAuthHeaderWithIntegrationName()
    {
        $handler = function ($request) {
            return $request['headers'];
        };

        $requestHandler = new RequestClientHeaderHandler($handler, 'integration:2.1.1');
        $response = $requestHandler([]);

        $this->assertArrayHasKey('X-Swiftype-Client', $response);
        $this->assertArrayHasKey('X-Swiftype-Client-Version', $response);
        $this->assertArrayHasKey('X-Swiftype-Integration', $response);
        $this->assertArrayHasKey('X-Swiftype-Integration-Version', $response);
        $this->assertEquals(['integration'], $response['X-Swiftype-Integration']);
        $this->assertEquals(['2.1.1'], $response['X-Swiftype-Integration-Version']);
    }
}
