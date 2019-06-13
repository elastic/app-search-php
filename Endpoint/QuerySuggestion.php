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
class QuerySuggestion extends \Elastic\OpenApi\Codegen\Endpoint\AbstractEndpoint
{
    // phpcs:disable
    /**
     * @var string
     */
    protected $method = 'POST';

    /**
     * @var string
     */
    protected $uri = '/engines/{engine_name}/query_suggestion';

    protected $routeParams = ['engine_name'];

    protected $paramWhitelist = ['query', 'types.documents.fields', 'size'];
    // phpcs:enable
}
