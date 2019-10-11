<?php
/**
 * This file is part of the Elastic App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elastic\AppSearch\Client\Endpoint;

/**
 * Implementation of the UpdateCredential endpoint.
 *
 * @package Elastic\AppSearch\Client\Endpoint
 */
class UpdateCredential extends \Elastic\OpenApi\Codegen\Endpoint\AbstractEndpoint
{
    // phpcs:disable
    /**
     * @var string
     */
    protected $method = 'PUT';

    /**
     * @var string
     */
    protected $uri = '/credentials/{key_name}';

    protected $routeParams = ['key_name'];

    protected $paramWhitelist = ['name', 'read', 'write', 'access_all_engines', 'engines'];
    // phpcs:enable
}
