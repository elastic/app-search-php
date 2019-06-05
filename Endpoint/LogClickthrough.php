<?php
/**
 * This file is part of the Elastic OpenAPI PHP code generator.
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
class LogClickthrough extends \Elastic\OpenApi\Codegen\Endpoint\AbstractEndpoint
{
    // phpcs:disable
    /**
     * @var string
     */
    protected $method = 'POST';

    /**
     * @var string
     */
    protected $uri = '/engines/{engine_name}/click';

    protected $routeParams = ['engine_name'];

    protected $paramWhitelist = ['query', 'document_id', 'request_id', 'tags'];
    // phpcs:enable
}
