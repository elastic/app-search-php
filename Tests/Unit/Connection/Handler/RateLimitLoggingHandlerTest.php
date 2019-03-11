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
        $this->logArray = [];

        $logger = $this->createMock(LoggerInterface::class);
        $logger->method('warning')->willReturnCallback(function($message) {
            $this->logArray[] = $message;
        });

        $handler = new RateLimitLoggingHandler(
            function ($request) use ($limit, $remaining){
                $headers = [
                    RateLimitLoggingHandler::RATE_LIMIT_LIMIT_HEADER_NAME => $limit,
                    RateLimitLoggingHandler::RATE_LIMIT_REMAINING_HEADER_NAME => $remaining,
                ];
                return new CompletedFutureArray(['headers' => array_filter($headers)]);
            },
            $logger
        );

        $handler([])->wait();

        if ($remaining && $limit && ($remaining / $limit) < RateLimitLoggingHandler::RATE_LIMIT_PERCENT_WARNING_TRESHOLD) {
            $this->assertNotEmpty($this->logArray);
        } else {
            $this->assertEmpty($this->logArray);
        }
    }
}
