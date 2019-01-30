<?php
/**
 * This file is part of the Swiftype App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swiftype\AppSearch\Tests\Integration;

/**
 * Integration test for the Documents API.
 *
 * TODO:
 * - Index to a invalid engine
 * - Update documents
 * - Test getting non existing documents
 * - Test ingesting invalid documents
 *
 * @package Swiftype\AppSearch\Test\Integration
 *
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class DocumentApiTest extends AbstractTestCase
{
    /**
     * @var string
     */
    public const SAMPLE_DOC_FILE = __DIR__ . '/_data/sampleDocs.yml';

    /**
     * Test all API methods to validate nominal behavior.
     *
     * Test scenario :
     * - Index documents from sample data and verify there is no indexing errors
     * - Retrieve all documents and check the number of docs is OK
     * - Retrieve documents by id
     * - Delete one document
     * - Retrieve all documents and check the number of docs is still OK
     * -
     */
    public function testApiMethods()
    {
        $documents = $this->getDocuments();

        $indexingResponse = self::$defaultClient->indexDocuments(self::$defaultEngine, $documents);
        $this->assertCount(count($documents), $indexingResponse);
        foreach ($indexingResponse as $documentIndexingResponse) {
            $this->assertEmpty($documentIndexingResponse['errors']);
        }

        $listParams = ['page' => ['current' => 1, 'size' => 25]];
        $documentListResponse = self::$defaultClient->listDocuments(self::$defaultEngine, $listParams);
        $this->assertEquals($listParams['page']['current'], $documentListResponse['meta']['page']['current']);
        $this->assertEquals($listParams['page']['size'], $documentListResponse['meta']['page']['size']);
        $this->assertCount(count($documents), $documentListResponse['results']);

        $docIds = array_column($documents, 'id');
        $this->assertEquals($documents, self::$defaultClient->getDocuments(self::$defaultEngine, $docIds));

        self::$defaultClient->deleteDocuments(self::$defaultEngine, [current($docIds)]);
        $documentListResponse = self::$defaultClient->listDocuments(self::$defaultEngine);
        $this->assertCount(count($documents) - 1, $documentListResponse['results']);
    }

    private function getDocuments()
    {
        $parser = new \Symfony\Component\Yaml\Parser();

        return $parser->parseFile(self::SAMPLE_DOC_FILE);
    }
}
