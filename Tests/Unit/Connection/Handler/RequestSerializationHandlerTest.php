<?php
/**
 * This file is part of the Swiftype App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swiftype\AppSearch\Tests\Unit\Connection\Handler;

use PHPUnit\Framework\TestCase;
use Swiftype\AppSearch\Connection\Handler\RequestSerializationHandler;
use Swiftype\AppSearch\Serializer\SmartSerializer;

/**
 * Unit tests for the request serialization handler.
 *
 * @package Swiftype\AppSearch\Test\Unit\Connection\Handler
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class RequestSerializationHandlerTest extends TestCase
{
    /**
     * Check data serialization accross various requests of the dataprovider.
     *
     * @dataProvider requestDataProvider
     */
    public function testSerializeRequest($request, $expectedBody)
    {
        $handler = $this->getHandler();
        $this->assertEquals($expectedBody, $handler($request));
    }

    /**
     * @return array
     */
    public function requestDataProvider()
    {
        $serializer = $this->getSerializer();
        $data = [
            [
                ['body' => ['foo' => 'bar']],
                $serializer->serialize(['foo' => 'bar']),
            ],
            [
                ['query_params' => ['foo' => 'bar']],
                $serializer->serialize(['foo' => 'bar']),
            ],
            [
                ['body' => ['foo' => 'bar'], 'query_params' => ['foo' => 'bar']],
                $serializer->serialize(['foo' => 'bar']),
            ],
            [
                ['body' => ['foo1' => 'bar1'], 'query_params' => ['foo2' => 'bar2']],
                $serializer->serialize(['foo1' => 'bar1', 'foo2' => 'bar2']),
            ],
            [
                [],
                null
            ],
        ];

        return $data;
    }

    /**
     * @return \Swiftype\AppSearch\Connection\Handler\RequestSerializationHandler
     */
    private function getHandler()
    {
        $handler = function ($request) {
            return $request['body'] ?? null;
        };

        $serializer = $this->getSerializer();

        return new RequestSerializationHandler($handler, $serializer);
    }

    /**
     * @return \Swiftype\AppSearch\Serializer\SmartSerializer
     */
    private function getSerializer()
    {
        return new SmartSerializer();
    }
}
