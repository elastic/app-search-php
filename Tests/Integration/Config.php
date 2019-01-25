<?php
/**
 * This file is part of the Swiftype App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swiftype\AppSearch\Tests\Integration;

/**
 * Allow to load config from environnement variable.
 *
 * @package Swiftype\AppSearch\Test\Integration
 *
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class Config
{
    public function getApiEndpoint()
    {
        return $_ENV['ST_API_ENDPOINT'];
    }

    public function getApiKey()
    {
        return $_ENV['ST_API_KEY'];
    }
}
