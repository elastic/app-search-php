<?php
/**
 * This file is part of the Swiftype App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swiftype\AppSearch\Exception;

use function InvalidArgumentException\__construct as sprintf;

/**
 * JSON error handling providing human friendly messages.
 *
 * @package Swiftype\AppSearch\Exception
 *
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class JsonErrorException extends \Exception implements SwiftypeException
{
    /**
     * @var mixed
     */
    private $input;

    /**
     * @var mixed
     */
    private $result;

    /**
     * @var array
     */
    private static $messages = [
        JSON_ERROR_DEPTH => 'The maximum stack depth has been exceeded',
        JSON_ERROR_STATE_MISMATCH => 'Invalid or malformed JSON',
        JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
        JSON_ERROR_SYNTAX => 'Syntax error',
        JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded',
        JSON_ERROR_RECURSION => 'One or more recursive references in the value to be encoded',
        JSON_ERROR_INF_OR_NAN => 'One or more NAN or INF values in the value to be encoded',
        JSON_ERROR_UNSUPPORTED_TYPE => 'A value of a type that cannot be encoded was given',
        JSON_ERROR_INVALID_PROPERTY_NAME => 'Decoding of value would result in invalid PHP property name',
        JSON_ERROR_UTF16 => 'Attempted to decode nonexistent UTF-16 code-point',
    ];

    /**
     * Constructor.
     *
     * @param mixed $code     error code
     * @param mixed $input    input provided to the serializer
     * @param mixed $result   result of the serializer op
     * @param mixed $previous previous exception
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($code, $input, $result, $previous = null)
    {
        if (true !== isset(self::$messages[$code])) {
            throw new \InvalidArgumentException(sprintf('Encountered unknown JSON error code: [%d]', $code));
        }

        parent::__construct(self::$messages[$code], $code, $previous);
        $this->input = $input;
        $this->result = $result;
    }

    /**
     * Input provided to the serializer.
     *
     * @return mixed
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * Result of the serializer op.
     *
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }
}
