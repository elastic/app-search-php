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
use Swiftype\AppSearch\Connection\Handler\RateLimitLoggingHandler;
use Psr\Log\LoggerInterface;

/**
 * Rate limit logger tests.
 *
 * @package Swiftype\AppSearch\Test\Unit\Connection\Handler
 *
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class RateLimitLoggingHandlerTest extends TestCase
{
    private $logArray = [];

    /**
     * Check rate limit warning are logged successfully.
     *
     * @testWith [null, null]
     *           [100, 50]
     *           [100, 5]
     */
    public function testExceptionTypes($limit, $remaining)
    {
        $response = ['headers' => array_filter($this->getResponseHeaders($limit, $remaining))];
        $handler = $this->getHandler($response);

        $handler([])->wait();

        if ($this->shouldLogWarning($limit, $remaining)) {
            $this->assertNotEmpty($this->logArray);
            $this->assertArrayHasKey('warning', $this->logArray);
        } else {
            $this->assertEmpty($this->logArray);
        }
    }

    /**
     * Return a the response handler used in test.
     *
     * @param array $response
     *
     * @return \Swiftype\AppSearch\Connection\Handler\RateLimitLoggingHandler
     */
    private function getHandler($response)
    {
        $responseCallback = function ($request) use ($response) {
            return new CompletedFutureArray($response);
        };

        return new RateLimitLoggingHandler($responseCallback, $this->getLoggerMock());
    }

    /**
     * Indicate if a warning should be logged or not.
     *
     * @param int|null $limit
     * @param int|null $remaining
     *
     * @return bool
     */
    private function shouldLogWarning($limit, $remaining)
    {
        return $limit && ($remaining / $limit) < RateLimitLoggingHandler::RATE_LIMIT_PERCENT_WARNING_TRESHOLD;
    }

    /**
     * Return response headers.
     *
     * @param int|null $limit
     * @param int|null $remaining
     *
     * @return array[]
     */
    private function getResponseHeaders($limit, $remaining)
    {
        return [
            RateLimitLoggingHandler::RATE_LIMIT_LIMIT_HEADER_NAME => [$limit],
            RateLimitLoggingHandler::RATE_LIMIT_REMAINING_HEADER_NAME => [$remaining],
        ];
    }

    /**
     * Create a mock for the logger interface.
     *
     * @return \Psr\Log\LoggerInterface
     */
    private function getLoggerMock()
    {
        $this->logArray = [];

        $logger = $this->createMock(LoggerInterface::class);
        $logger->method('warning')->willReturnCallback(function ($message) {
            $this->logArray['warning'][] = $message;
        });

        return $logger;
    }
}
