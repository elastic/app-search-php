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
 *
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class DeleteCuration extends AbstractEndpoint
{
    // phpcs:disable
    /**
     * @var string
     */
    protected $method = 'DELETE';

    /**
     * @var string
     */
    protected $uri = '/engines/{engine_name}/curations/{curation_id}';

    protected $routeParams = ['engine_name', 'curation_id'];
    // phpcs:enable
}
