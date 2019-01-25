<?php
/**
 * This file is part of the Swiftype App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swiftype\AppSearch\Tests\Unit\Connection\Handler;

use GuzzleHttp\Ring\Future\CompletedFutureArray;
use PHPUnit\Framework\TestCase;
use Swiftype\AppSearch\Connection\Handler\ConnectionErrorHandler;

/**
 * Check connection error are turns into comprehensive exceptions by the handler.
 *
 * @package Swiftype\AppSearch\Test\Unit\Connection\Handler
 *
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class ConnectionErrornHandlerTest extends TestCase
{
    /**
     * Check the exception is thrown when needed.
     *
     * @dataProvider errorDataProvider
     */
    public function testExceptionTypes($response, $exceptionClass, $exceptionMessage)
    {
        if (null != $exceptionClass) {
            $this->expectException($exceptionClass);
            $this->expectExceptionMessage($exceptionMessage);
        }

        $handler = new ConnectionErrorHandler(
            function ($request) use ($response) {
                return new CompletedFutureArray($response);
            }
        );

        $handlerResponse = $handler([])->wait();

        if (null == $exceptionClass) {
            $this->assertEquals($response, $handlerResponse);
        }
    }

    /**
     * @return array
     */
    public function errorDataProvider()
    {
        $data = [
          [
            ['error' => new \Exception('Unknown exception')],
            \Swiftype\AppSearch\Exception\ConnectionException::class,
            'Unknown exception',
          ],
          [
            ['error' => new \Exception('Unknown exception'), 'curl' => []],
            \Swiftype\AppSearch\Exception\ConnectionException::class,
            'Unknown exception',
          ],
          [
            ['error' => new \Exception('Could not resolve host'), 'curl' => ['errno' => CURLE_COULDNT_RESOLVE_HOST]],
            \Swiftype\AppSearch\Exception\CouldNotResolveHostException::class,
            'Could not resolve host',
          ],
          [
            ['error' => new \Exception('Could not connect to host'), 'curl' => ['errno' => CURLE_COULDNT_CONNECT]],
            \Swiftype\AppSearch\Exception\CouldNotConnectToHostException::class,
            'Could not connect to host',
          ],
          [
            ['error' => new \Exception('Timeout exception'), 'curl' => ['errno' => CURLE_OPERATION_TIMEOUTED]],
            \Swiftype\AppSearch\Exception\OperationTimeoutException::class,
            'Timeout exception',
          ],
          [
            ['foo' => 'bar'],
            null,
            null,
          ],
        ];

        return $data;
    }
}
