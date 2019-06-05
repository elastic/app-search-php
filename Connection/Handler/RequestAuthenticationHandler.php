<?php
/**
 * This file is part of the Elastic App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elastic\AppSearch\Client\Connection\Handler;

use GuzzleHttp\Ring\Core;

/**
 * This handler add automatically the API Key to the request.
 *
 * @package Elastic\AppSearch\Client\Connection\Handler
 *
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class RequestAuthenticationHandler
{
    /**
     * @var string
     */
    const HEADER_NAME = 'Authorization';

    /**
     * @var string
     */
    const HEADER_VALUE_PATTERN = 'Bearer %s';

    /**
     * @var callable
     */
    private $handler;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * Constructor.
     *
     * @param callable $handler original handler
     * @param string   $apiKey  API Key
     */
    public function __construct(callable $handler, $apiKey)
    {
        $this->handler = $handler;
        $this->apiKey = $apiKey;
    }

    /**
     * Add API key before calling the original handler.
     *
     * @param array $request original request
     *
     * @return array
     */
    public function __invoke($request)
    {
        $handler = $this->handler;

        $headerValue = [sprintf(self::HEADER_VALUE_PATTERN, $this->apiKey)];
        $request = Core::setHeader($request, self::HEADER_NAME, $headerValue);

        return $handler($request);
    }
}
