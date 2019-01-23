<?php
/**
 * This file is part of the Swiftype App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swiftype\AppSearch\Exception;

/**
 * Denote a value that is outside the normally accepted values.
 *
 * @package Swiftype\AppSearch\Exception
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class UnexpectedValueException extends \UnexpectedValueException implements SwiftypeException
{
}
