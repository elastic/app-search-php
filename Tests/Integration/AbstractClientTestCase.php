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
        $clientBuilder = new ClientBuilder();
        self::$defaultClient = $clientBuilder->create(getenv('AS_URL'), getenv('AS_PRIVATE_KEY'))->build();
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
        $enginePrefix = getenv('AS_ENGINE_NAME') ? getenv('AS_ENGINE_NAME') : 'php-integration-test';
        $className = explode('\\', get_called_class());
        $engineSuffix = strtolower(end($className));

        return str_replace('.', '-', sprintf('%s-%s', $enginePrefix, $engineSuffix));
    }
}
