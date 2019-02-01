<?php
/**
 * This file is part of the Swiftype App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swiftype\AppSearch\Tests\Integration;

use PHPUnit\Framework\TestCase;
use Swiftype\AppSearch\ClientBuilder;
use Swiftype\AppSearch\Exception\BadRequestException;
use Swiftype\AppSearch\Exception\NotFoundException;

/**
 * A base class for running client tests.
 *
 * @package Swiftype\AppSearch\Test\Integration
 *
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class AbstractTestCase extends TestCase
{
    public const MAX_TRY_SETUP       = 10;
    public const RETRY_SETUP_TIMEOUT = 500000;

    /**
     * @var \Swiftype\AppSearch\Client
     */
    protected static $defaultClient;

    /**
     * @var string
     */
    protected static $defaultEngine;

    /**
     * Init a default client to run all the tests.
     */
    public static function setupBeforeClass()
    {
        $config = new \Swiftype\AppSearch\Tests\Integration\Helper\Config();
        self::$defaultEngine = $config->getEngineName();
        self::$defaultClient = ClientBuilder::create($config->getApiEndpoint(), $config->getApiKey())->build();
    }

    /**
     * Create the default engine when starting a test.
     */
    public function setUp()
    {
        $tries = 0;

        try {
            // Make sure no test engine already exists.
            self::$defaultClient->deleteEngine(self::$defaultEngine);
        } catch (NotFoundException $e) {
            // Engine is already deleted. Exception can be ignored.
        }

        // Try to create the engine as long you can try.
        do {
            try {
                $tries++;
                self::$defaultClient->createEngine(['name' => self::$defaultEngine]);
                $engineCreated = true;
            } catch (BadRequestException $e) {
                if ($tries > self::MAX_TRY_SETUP) {
                    throw $e;
                }
                $engineCreated = false;
                usleep(self::RETRY_SETUP_TIMEOUT);
            }
        } while (!$engineCreated);
    }

    /**
     * Destroy the default engine when ending a test.
     */
    public function tearDown()
    {
        try {
            self::$defaultClient->deleteEngine(self::$defaultEngine);
        } catch (NotFoundException $e) {
            // Engine is already deleted. Exception can be ignored.
        }
    }
}
