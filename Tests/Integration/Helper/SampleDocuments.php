<?php
/**
 * This file is part of the Swiftype App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swiftype\AppSearch\Tests\Integration\Helper;

/**
 * Load some sample documents from a YAML file.
 *
 * @package Swiftype\AppSearch\Test\Integration
 *
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class SampleDocuments
{
    /**
     * @var string
     */
    public const DEFAULT_FILE = __DIR__ . '/../_data/sampleDocs.yml';

    /**
     * @var array
     */
    private $documents = [];

    /**
     * Build a sample datasource from a filename.
     *
     * @param string $filename
     */
    public function __construct($filename = self::DEFAULT_FILE)
    {
        $parser = new \Symfony\Component\Yaml\Parser();
        $this->documents = $parser->parseFile($filename);
    }

    /**
     * Get loaded documents.
     *
     * @return array
     */
    public function getDocuments()
    {
        return $this->documents;
    }
}
