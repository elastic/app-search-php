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
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache2
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
        var_dump($_ENV);
        self::$defaultClient = ClientBuilder::create(getenv('ST_API_ENDPOINT'), getenv('ST_API_KEY'))->build();
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
        $enginePrefix = getenv('ST_ENGINE_NAME') ?: 'php-integration-test';
        $className = explode('\\', get_called_class());
        $engineSuffix = strtolower(end($className));

        return  sprintf('%s-%s', $enginePrefix, $engineSuffix);
    }
}
