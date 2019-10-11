<?php
/**
 * This file is part of the Elastic App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elastic\AppSearch\Client\Endpoint;

/**
 * Implementation of the ListCredentials endpoint.
 *
 * @package Elastic\AppSearch\Client\Endpoint
 */
class ListCredentials extends \Elastic\OpenApi\Codegen\Endpoint\AbstractEndpoint
{
    // phpcs:disable
    /**
     * @var string
     */
    protected $method = 'GET';

    /**
     * @var string
     */
    protected $uri = '/credentials';

    protected $paramWhitelist = ['page.current', 'page.size'];
    // phpcs:enable
}
