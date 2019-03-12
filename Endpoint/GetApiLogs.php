<?php
/**
 * This file is part of the Swiftype PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swiftype\AppSearch\Endpoint;

/**
 * Implementation of the  endpoint.
 *
 * @package Swiftype\AppSearch\Endpoint
 */
class GetApiLogs extends \Swiftype\Endpoint\AbstractEndpoint
{
    // phpcs:disable
    /**
     * @var string
     */
    protected $method = 'GET';

    /**
     * @var string
     */
    protected $uri = '/engines/{engine_name}/logs/api';

    protected $routeParams = ['engine_name'];

    protected $paramWhitelist = ['filters.date.from', 'filters.date.to', 'page.current', 'page.size', 'query', 'filters.status', 'filters.method', 'sort_direction'];
    // phpcs:enable
}
