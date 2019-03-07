<?php
/**
 * This file is part of the Swiftype App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swiftype\AppSearch\Connection\Handler;

use GuzzleHttp\Ring\Core;

/**
 * This handler add automatically reporting headers to the request.
 *
 * @package Swiftype\AppSearch\Connection\Handler
 *
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class RequestClientHeaderHandler
{
    /**
     * @var string
     */
    const CLIENT_NAME_HEADER = 'X-Swiftype-Client';

    /**
     * @var string
     */
    const CLIENT_NAME_VALUE = 'swiftype-app-search-php';

    /**
     * @var string
     */
    const CLIENT_VERSION_HEADER = 'X-Swiftype-Client-Version';

    /**
     * @var string
     */
    const CLIENT_VERSION_VALUE = '1.0.0-alpha';

    /**
     * Constructor.
     *
     * @param callable $handler original handler
     */
    public function __construct(callable $handler)
    {
        $this->handler = $handler;
    }

    /**
     * Add reporting headers before calling the original handler.
     *
     * @param array $request original request
     *
     * @return array
     */
    public function __invoke($request)
    {
        $handler = $this->handler;

        $request = Core::setHeader($request, self::CLIENT_NAME_HEADER, [self::CLIENT_NAME_VALUE]);
        $request = Core::setHeader($request, self::CLIENT_VERSION_HEADER, [self::CLIENT_VERSION_VALUE]);

        return $handler($request);
    }
}
