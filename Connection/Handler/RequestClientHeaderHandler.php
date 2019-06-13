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
 * This handler add automatically reporting headers to the request.
 *
 * @package Elastic\AppSearch\Client\Connection\Handler
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache2
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
    const CLIENT_INTEGRATION_NAME_HEADER = 'X-Swiftype-Integration';

    /**
     * @var string
     */
    const CLIENT_INTEGRATION_VERSION_HEADER = 'X-Swiftype-Integration-Version';

    /**
     * @var string
     */
    const CLIENT_VERSION_VALUE = '1.0.0-alpha';

    /**
     * @var callable
     */
    private $handler;

    /**
     * @var string
     */
    private $integration;

    /**
     * Constructor.
     *
     * @param callable $handler     Original handler.
     * @param string   $integration Integration name & version.
     */
    public function __construct(callable $handler, $integration = null)
    {
        $this->handler = $handler;
        $this->integration = $integration;
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

        if (null !== $this->integration) {
            list($integrationName, $integrationVersion) = explode(':', $this->integration);
            if ($integrationName) {
                $request = Core::setHeader($request, self::CLIENT_INTEGRATION_NAME_HEADER, [$integrationName]);
            }
            if ($integrationVersion) {
                $request = Core::setHeader($request, self::CLIENT_INTEGRATION_VERSION_HEADER, [$integrationVersion]);
            }
        }

        return $handler($request);
    }
}
