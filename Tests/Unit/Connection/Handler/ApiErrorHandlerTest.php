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
use Swiftype\AppSearch\Connection\Handler\ApiErrorHandler;

/**
 * Check API errors are turns into comprehensive exceptions by the handler.
 *
 * @package Swiftype\AppSearch\Test\Unit\Connection\Handler
 *
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class ApiErrorHandlerTest extends TestCase
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

        $handler = new ApiErrorHandler(
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
        $parser = new \Symfony\Component\Yaml\Parser();

        return $parser->parseFile(__DIR__.'/_data/apiError.yml');
    }
}
