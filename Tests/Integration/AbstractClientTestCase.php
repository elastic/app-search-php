<?php
/**
 * This file is part of the Elastic App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elastic\AppSearch\Client\Tests\Integration;

use PHPUnit\Framework\TestCase;
use Elastic\AppSearch\Client\ClientBuilder;

/**
 * A base class for running client tests.
 *
 * @package Elastic\AppSearch\Client\Test\Integration
 *
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class AbstractClientTestCase extends TestCase
{
    /**
     * @var \Elastic\AppSearch\Client\Client
     */
    private static $defaultClient;

    /**
     * Init a default client to run all the tests.
     */
    public static function setupBeforeClass()
    {
        self::$defaultClient = ClientBuilder::create($_ENV['ST_API_ENDPOINT'], $_ENV['ST_API_KEY'])->build();
    }

    /**
     * @return \Elastic\AppSearch\Client\Client
     */
    protected static function getDefaultClient()
    {
        return self::$defaultClient;
    }

    /**
     * @return string
     */
    protected static function getDefaultEngineName()
    {
        $enginePrefix = isset($_ENV['ST_ENGINE_NAME']) ? $_ENV['ST_ENGINE_NAME'] : 'php-integration-test';
        $className = explode('\\', get_called_class());
        $engineSuffix = strtolower(end($className));

        return  sprintf('%s-%s', $enginePrefix, $engineSuffix);
    }
}
