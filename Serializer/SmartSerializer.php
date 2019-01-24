<?php
/**
 * This file is part of the Swiftype App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swiftype\AppSearch\Serializer;

use Swiftype\AppSearch\Exception\JsonErrorException;

/**
 * Default serializer used by the client.
 *
 * @package Swiftype\AppSearch\Serializer
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class SmartSerializer implements SerializerInterface
{
    /**
     * @var string
     */
    private $PHP_VERSION;

    /**
     * Default constructor.
     */
    public function __construct()
    {
        $this->PHP_VERSION = phpversion();
    }

    /**
     * Serialize assoc array into JSON string
     *
     * @param string|array $data Assoc array to encode into JSON
     *
     * @return string
     */
    public function serialize($data)
    {
        if (is_string($data) === true) {
            return $data;
        } else {
            if (version_compare($this->PHP_VERSION, '5.6.6', '<') || ! defined('JSON_PRESERVE_ZERO_FRACTION')) {
                $data = json_encode($data);
            } else {
                $data = json_encode($data, JSON_PRESERVE_ZERO_FRACTION);
            }
            if ($data === '[]') {
                return '{}';
            } else {
                return $data;
            }
        }
    }

    /**
     * Deserialize by introspecting content_type. Tries to deserialize JSON,
     * otherwise returns string
     *
     * @throws JsonErrorException
     *
     * @param string $data JSON encoded string
     * @param array  $headers Response Headers
     *
     * @return array
     */
    public function deserialize($data, $headers)
    {
        if (isset($headers['content_type']) === true && strpos($headers['content_type'], 'json') === false) {
            return $data;
        }

        return $this->decode($data);
    }

    /**
     * @todo For 2.0, remove the E_NOTICE check before raising the exception.
     *
     * @throws JsonErrorException
     *
     * @param $data
     *
     * @return array
     */
    private function decode($data)
    {
        if ($data === null || strlen($data) === 0) {
            return "";
        }

        $result = @json_decode($data, true);

        if (json_last_error() !== JSON_ERROR_NONE && (error_reporting() & E_NOTICE) === E_NOTICE) {
            $e = new JsonErrorException(json_last_error(), $data, $result);
            throw $e;
        }

        return $result;
    }
}
