<?php
/**
 * This file is part of the Elastic App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elastic\AppSearch\Client\Tests\Integration;

/**
 * Integration test for the Schema API.
 *
 * @package Elastic\AppSearch\Client\Test\Integration
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache2
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class SchemaApiTest extends AbstractEngineTestCase
{
    /**
     * @var bool
     */
    protected static $importSampleDocs = true;

    /**
     * Test getting the schema.
     */
    public function testGetSchema()
    {
        $client = $this->getDefaultClient();
        $engineName = $this->getDefaultEngineName();

        $schema = $client->getSchema($engineName);
        $this->assertArrayHasKey('title', $schema);
        $this->assertEquals('text', $schema['title']);
    }

    /**
     * Test updating the schema.
     *
     * @param string $fieldName
     * @param string $fieldType
     *
     * @testWith ["string_field", "text"]
     *           ["date_field", "date"]
     *           ["number_field", "number"]
     *           ["geo_field", "geolocation"]
     */
    public function testUpdateSchema($fieldName, $fieldType)
    {
        $client = $this->getDefaultClient();
        $engineName = $this->getDefaultEngineName();
        $schema = $client->updateSchema($engineName, [$fieldName => $fieldType]);

        $this->assertArrayHasKey($fieldName, $schema);
        $this->assertEquals($fieldType, $schema[$fieldName]);
    }

    /**
     * Test invalid schema updates.
     *
     * @param string $fieldName
     * @param string $fieldType
     *
     * @expectedException \Elastic\OpenApi\Codegen\Exception\BadRequestException
     *
     * @testWith ["string_field", "not-a-valid-type"]
     *           ["id", "number"]
     *           ["12", "text"]
     *           ["invalid field name", "text"]
     *           ["_invalid_field_name", "text"]
     *           ["invalid-field-name", "text"]
     *           ["invalidFieldName", "text"]
     *           ["invalid.field.name", "text"]
     *           ["INVALID", "text"]
     */
    public function testInvalidSchemaUpdate($fieldName, $fieldType)
    {
        $client = $this->getDefaultClient();
        $engineName = $this->getDefaultEngineName();
        $client->updateSchema($engineName, [$fieldName => $fieldType]);
    }
}
