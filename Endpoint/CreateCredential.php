<?php
/**
 * This file is part of the Elastic App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elastic\AppSearch\Client\Endpoint;

/**
 * Implementation of the CreateCredential endpoint.
 *
 * @package Elastic\AppSearch\Client\Endpoint
 */
class CreateCredential extends \Elastic\OpenApi\Codegen\Endpoint\AbstractEndpoint
{
    // phpcs:disable
    /**
     * @var string
     */
    protected $method = 'POST';

    /**
     * @var string
     */
    protected $uri = '/credentials';

    protected $paramWhitelist = ['name', 'type', 'read', 'write', 'access_all_engines', 'engines'];
    // phpcs:enable
}
