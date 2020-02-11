<?php
/**
 * This file is part of the Elastic App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elastic\AppSearch\Client;

/**
 * Client implementation.
 *
 * @package Elastic\AppSearch\Client
 */
class Client extends AbstractClient
{
    /**
     * Creates a new engine.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/engines#create
     *
     * @param string $name     Engine name.
     * @param string $language Engine language (null for universal).
     *
     * @return array
     */
    public function createEngine($name, $language = null)
    {
        return $this->doCreateEngine($name, $language);
    }

    /**
     * Creates a new meta engine.
     *
     * Documentation: https://swiftype.com/documentation/app-search/api/engines#create
     *
     * @param string   $name          Engine name.
     * @param string[] $sourceEngines Source engines list.
     *
     * @return array
     */
    public function createMetaEngine($name, array $sourceEngines)
    {
        return $this->doCreateEngine($name, null, 'meta', $sourceEngines);
    }
}
