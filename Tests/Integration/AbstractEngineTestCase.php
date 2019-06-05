<?php
/**
 * This file is part of the Elastic App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elastic\AppSearch\Client\Tests\Integration;

use Elastic\OpenApi\Codegen\Exception\NotFoundException;

/**
 * A base class for running client tests with a default engine and some sample optional docs.
 *
 * @package Elastic\AppSearch\Client\Test\Integration
 *
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class AbstractEngineTestCase extends AbstractClientTestCase
{
    /**
     * @var string
     */
    const DOC_SAMPLE_FILE = __DIR__ . '/_data/sampleDocs.yml';

    /**
     * @var int
     */
    const SYNC_RETRY_INTERVAL = 500000;

    /**
     * @var bool
     */
    protected static $importSampleDocs = false;

    /**
     * Create the default engine before lauching tests.
     * Optionally import sample data and wait for them to be searchable.
     */
    public static function setupBeforeClass()
    {
        parent::setUpBeforeClass();
        $tryDelete = true;
        $hasEngine = false;

        do {
            try {
                self::getDefaultClient()->getEngine(self::getDefaultEngineName());
                $hasEngine = true;
            } catch (NotFoundException $e) {
                $hasEngine = false;
            }
            if ($hasEngine && $tryDelete) {
                self::tearDownAfterClass();
                $tryDelete = false;
            }
        } while ($hasEngine);

        self::getDefaultClient()->createEngine(self::getDefaultEngineName());

        if (static::$importSampleDocs) {
            self::importSampleDocuments();
        }
    }

    /**
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
        $client = self::getDefaultClient();
        $engineName = self::getDefaultEngineName();
        $documents = self::getSampleDocuments();

        $indexingResponse = $client->indexDocuments($engineName, $documents);

        if ($waitForSearchableDocs) {
            $isReady = false;

            while (false === $isReady) {
                usleep(self::SYNC_RETRY_INTERVAL);

                // We also wait for the schema to be synced.
                $schema = $client->getSchema($engineName);
                $isSchemaSynced = !empty($schema);

                if ($isSchemaSynced) {
                    // We wait for the docs to be searchable before launching the test.
                    $searchResponse = $client->search($engineName, '');
                    $areDocsSynced = $searchResponse['meta']['page']['total_results'] == count($documents);

                    $isReady = $isSchemaSynced && $areDocsSynced;
                }
            }
        }

        return $indexingResponse;
    }

    protected static function getSampleDocuments()
    {
        $parser = new \Symfony\Component\Yaml\Parser();

        return $parser->parseFile(self::DOC_SAMPLE_FILE);
    }
}
