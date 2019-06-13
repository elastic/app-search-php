<?php
/**
 * This file is part of the Elastic OpenAPI PHP code generator.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elastic\AppSearch\Client\Endpoint;

/**
 * Implementation of the  endpoint.
 *
 * @package Elastic\AppSearch\Client\Endpoint
 */
class ListDocuments extends \Elastic\OpenApi\Codegen\Endpoint\AbstractEndpoint
{
    // phpcs:disable
    /**
     * @var string
     */
    protected $method = 'GET';

    /**
     * @var string
     */
    protected $uri = '/engines/{engine_name}/documents/list';

    protected $routeParams = ['engine_name'];

    protected $paramWhitelist = ['page.current', 'page.size'];
    // phpcs:enable
}
