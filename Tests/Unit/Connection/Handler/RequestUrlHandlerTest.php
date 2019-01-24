<?php
/**
 * This file is part of the Swiftype App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swiftype\AppSearch\Tests\Unit\Connection\Handler;

use PHPUnit\Framework\TestCase;
use Swiftype\AppSearch\Connection\Handler\RequestUrlHandler;

/**
 * Unit tests for the authentication handler.
 *
 * @package Swiftype\AppSearch\Test\Unit\Connection\Handler
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class RequestUrlHandlerTest extends TestCase
{
    /**
     * Check the data are correct for host and scheme in the request.
     * Additionally check if the api prefix is append to the URI.
     *
     * @dataProvider urlDataProvider
     */
    public function testUrlData($apiEndpoint, $uri, $expectedUri, $expectedHost, $expectedScheme)
    {
        $handler = function ($request) {
            return $request;
        };

        $urlHandler = new RequestUrlHandler($handler, $apiEndpoint);
        $request = $urlHandler(['uri' => $uri]);

        $this->assertEquals($expectedUri, $request['uri']);
        $this->assertEquals([$expectedHost], $request['headers']['host']);
        $this->assertEquals($expectedScheme, $request['scheme']);
    }

    /**
     * @return array
     */
    public function urlDataProvider()
    {
        return [
            ["http://test.com", "/foo", "/api/as/v1/foo", "test.com", "http"],
            ["https://test.com", "/foo", "/api/as/v1/foo", "test.com", "https"],
            ["https://test.com", "/", "/api/as/v1/", "test.com", "https"],
            ["http://test.com", "/", "/api/as/v1/", "test.com", "http"],
            ["https://test", "/", "/api/as/v1/", "test", "https"],
            ["http://test", "/", "/api/as/v1/", "test", "http"],
            ["https://test/foo", "/", "/api/as/v1/", "test", "https"],
            ["http://test/foo", "/", "/api/as/v1/", "test", "http"],
            ["http://localhost:3200/foo", "/", "/api/as/v1/", "localhost:3200", "http"],
            ["https://localhost:3200/foo", "/", "/api/as/v1/", "localhost:3200", "https"],
        ];
    }
}
