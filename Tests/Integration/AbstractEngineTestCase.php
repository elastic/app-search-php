<?php
/**
 * This file is part of the Swiftype App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swiftype\AppSearch\Tests\Integration;

/**
 * A base class for running client tests with a default engine and some sample optional docs.
 *
 * @package Swiftype\AppSearch\Test\Integration
 *
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class AbstractEngineTestCase extends AbstractClientTestCase
{
    /**
     * @var string
     */
    public const DOC_SAMPLE_FILE = __DIR__ . '/_data/sampleDocs.yml';

    /**
     * @var integer
     */
    public const SYNC_RETRY_INTERVAL = 500000;

    /**
     * @var boolean
     */
    protected static $importSampleDocs = false;

    /**
     * Create the default engine before lauching tests.
     * Optionally import sample data and wait for them to be searchable.
     */
    public static function setupBeforeClass()
    {
        parent::setUpBeforeClass();
        self::getDefaultClient()->createEngine(['name' => self::getDefaultEngineName()]);

        if (static::$importSampleDocs) {
            self::importSampleDocuments();
        }
    }

    /**
     *
     * Delete the default engine before exiting the class.
     */
    public static function tearDownAfterClass()
    {
        self::getDefaultClient()->deleteEngine(self::getDefaultEngineName());
    }

    /**
     * Import sample data into the default engine.
     */
    protected static function importSampleDocuments($waitForSearchableDocs = true)
    {
        $client     = self::getDefaultClient();
        $engineName = self::getDefaultEngineName();
        $documents  = self::getSampleDocuments();

        $indexingResponse = $client->indexDocuments($engineName, $documents);

        if ($waitForSearchableDocs) {
            do {
                // We wait for the doc to be searchable before launching the test.
                $searchResponse = $client->search($engineName, ['query' => '']);
                $areDocsSynced = $searchResponse['meta']['page']['total_results'] == count($documents);

                // We also wait for the schema to be synced.
                $schema = $client->getSchema($engineName);
                $isSchemaSynced = !empty($schema);
                usleep(self::SYNC_RETRY_INTERVAL);
            } while (!($areDocsSynced && $isSchemaSynced));
        }

        return $indexingResponse;
    }

    protected static function getSampleDocuments()
    {
        $parser     = new \Symfony\Component\Yaml\Parser();
        return $parser->parseFile(self::DOC_SAMPLE_FILE);
    }
}
