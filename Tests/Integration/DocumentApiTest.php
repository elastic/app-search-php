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
     * @var array
     */
    private $documents;


    /**
     * Test indexing documents from sample data and check there is no errors.
     */
    public function testIndexing()
    {
        $documents = $this->getDocuments();

        $indexingResponse = self::$defaultClient->indexDocuments(self::$defaultEngine, $documents);
        $this->assertCount(count($documents), $indexingResponse);
        foreach ($indexingResponse as $documentIndexingResponse) {
            $this->assertEmpty($documentIndexingResponse['errors']);
        }
    }

    /**
     * Test getting documents after they have been indexed.
     */
    public function testGetDocuments()
    {
        $documents = $this->getDocuments();
        $documentIds = array_column($documents, 'id');

        self::$defaultClient->indexDocuments(self::$defaultEngine, $documents);
        $this->assertEquals($documents, self::$defaultClient->getDocuments(self::$defaultEngine, $documentIds));
    }

    /**
     * Test listing documents after they have been indexed.
     */
    public function testListDocuments()
    {
        $documents = $this->getDocuments();
        self::$defaultClient->indexDocuments(self::$defaultEngine, $documents);

        $listParams = ['page' => ['current' => 1, 'size' => 25]];
        $documentListResponse = self::$defaultClient->listDocuments(self::$defaultEngine, $listParams);
        $this->assertEquals($listParams['page']['current'], $documentListResponse['meta']['page']['current']);
        $this->assertEquals($listParams['page']['size'], $documentListResponse['meta']['page']['size']);
        $this->assertCount(count($documents), $documentListResponse['results']);
    }

    /**
     * Test delete documents after they have been indexed.
     */
    public function testDeleteDocuments()
    {
        $documents = $this->getDocuments();
        $documentIds = array_column($documents, 'id');
        self::$defaultClient->indexDocuments(self::$defaultEngine, $documents);

        self::$defaultClient->deleteDocuments(self::$defaultEngine, [current($documentIds)]);

        $documentListResponse = self::$defaultClient->listDocuments(self::$defaultEngine);
        $this->assertCount(count($documents) - 1, $documentListResponse['results']);
    }

    /**
     * Test delete documents after they have been indexed.
     */
    public function testUpdatingDocuments()
    {
        $documents = $this->getDocuments();
        self::$defaultClient->updateSchema(self::$defaultEngine, ['title' => 'text']);
        self::$defaultClient->indexDocuments(self::$defaultEngine, $documents);

        $documentsUpdates = [['id' => $documents[0]['id'], 'title' => 'foo']];
        $updateResponse = self::$defaultClient->updateDocuments(self::$defaultEngine, $documentsUpdates);
        $this->assertEmpty(current($updateResponse)['errors']);
    }

    /**
     * Test getting a document that does not exists.
     */
    public function testGetNonExistingDocuments()
    {
        $this->assertEquals([null], self::$defaultClient->getDocuments(self::$defaultEngine, ['foo']));
    }

    /**
     * Test index in an engine that does not exists.
     *
     * @expectedException \Swiftype\AppSearch\Exception\NotFoundException
     */
    public function testIndexingInInvalidEngine()
    {
        self::$defaultClient->getDocuments("not-an-engine", $this->getDocuments());
    }

    private function getDocuments()
    {
        if ($this->documents == null) {
            $this->documents = (new Helper\SampleDocuments())->getDocuments();
        }

        return $this->documents;
    }
}
