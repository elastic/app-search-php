<?php
/**
 * This file is part of the Swiftype App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swiftype\AppSearch\Endpoint;

/**
 * Endpoint builder implementation.
 *
 * @package Swiftype\AppSearch\Endpoint
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class Builder
{
    /**
     * Create an endpoint from name.
     */
    public function __invoke($endpointName)
    {
        $className = sprintf("%s\\%s", __NAMESPACE__, $endpointName);

        return new $className();
    }
}
