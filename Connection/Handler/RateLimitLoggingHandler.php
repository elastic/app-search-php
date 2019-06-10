<?php
/**
 * This file is part of the Elastic App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elastic\AppSearch\Client\Connection\Handler;

use GuzzleHttp\Ring\Core;
use Psr\Log\LoggerInterface;

/**
 * Log a warning if more than 90 percent of the allowed rate limit is consumed.
 *
 * @package Elastic\AppSearch\Client\Connection\Handler
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache2
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class RateLimitLoggingHandler
{
    /**
     * @var int
     */
    const RATE_LIMIT_PERCENT_WARNING_TRESHOLD = 0.1;

    /**
     * @var string
     */
    const RATE_LIMIT_LIMIT_HEADER_NAME = 'X-RateLimit-Limit';

    /**
     * @var string
     */
    const RATE_LIMIT_REMAINING_HEADER_NAME = 'X-RateLimit-Remaining';

    /**
     * @var string
     */
    const RETRY_AFTER_HEADER_NAME = 'Retry-After';

    /**
     * @var callable
     */
    private $handler;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Constructor.
     *
     * @param callable $handler original handler
     */
    public function __construct(callable $handler, LoggerInterface $logger)
    {
        $this->handler = $handler;
        $this->logger = $logger;
    }

    /**
     * Proxy the response and throw an exception if a http error is detected.
     *
     * @param array $request request
     *
     * @return array
     */
    public function __invoke($request)
    {
        $handler = $this->handler;
        $response = Core::proxy($handler($request), function ($response) {
            if ($this->isRateLimitWarning($response)) {
                $message = sprintf(
                    'AppSearch Rate Limit: %s remaining of %s allowed',
                    $this->getRemainingRateLimit($response),
                    $this->getAllowedRateLimit($response)
                );
                $this->logger->warning($message);
            }

            return $response;
        });

        return $response;
    }

    /**
     * Indicate if a warning should be logged or not.
     *
     * @param array $response
     *
     * @return bool
     */
    private function isRateLimitWarning($response)
    {
        $allowedRateLimit = $this->getAllowedRateLimit($response);
        $remainingRateLimit = $this->getRemainingRateLimit($response);

        if (null === $allowedRateLimit || null === $remainingRateLimit) {
            return false;
        }

        return ($remainingRateLimit / $allowedRateLimit) < self::RATE_LIMIT_PERCENT_WARNING_TRESHOLD;
    }

    /**
     * Read the allowed rate limit from response header.
     *
     * @param array $response
     *
     * @return null|int
     */
    private function getAllowedRateLimit($response)
    {
        return $this->getHeaderValue($response, self::RATE_LIMIT_LIMIT_HEADER_NAME);
    }

    /**
     * Read the remaining rate limit from response header.
     *
     * @param array $response
     *
     * @return null|int
     */
    private function getRemainingRateLimit($response)
    {
        return $this->getHeaderValue($response, self::RATE_LIMIT_REMAINING_HEADER_NAME);
    }

    /**
     * Read an unique value from response headers.
     *
     * @param array $response
     *
     * @return null|int
     */
    private function getHeaderValue($response, $headerName)
    {
        return Core::firstHeader($response, $headerName);
    }
}
