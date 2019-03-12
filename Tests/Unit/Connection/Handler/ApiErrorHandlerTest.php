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
use Swiftype\AppSearch\Connection\Handler\RateLimitLoggingHandler;

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
     * Test if the ApiRateExceededException excecption contains valid limit and retry values.
     *
     * @param int $limit
     * @param int $retryAfter
     */
    public function testApiRateLimited($limit = 10, $retryAfter = 20)
    {
        $headers = [
            RateLimitLoggingHandler::RATE_LIMIT_LIMIT_HEADER_NAME => [$limit],
            RateLimitLoggingHandler::RETRY_AFTER_HEADER_NAME => [$retryAfter],
        ];
        $handler = new ApiErrorHandler(
            function ($request) use ($headers) {
                return new CompletedFutureArray(['status' => 429, 'headers' => $headers]);
            }
        );

        try {
            $handler([])->wait();
        } catch (\Swiftype\AppSearch\Exception\ApiRateExceededException $e) {
            $this->assertEquals($limit, $e->getApiRateLimit());
            $this->assertEquals($retryAfter, $e->getRetryAfter());
        }
    }

    /**
     * @return array
     */
    public function errorDataProvider()
    {
        $parser = new \Symfony\Component\Yaml\Parser();

        return $parser->parseFile(__DIR__ . '/_data/apiError.yml');
    }
}
