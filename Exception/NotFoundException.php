<?php
/**
 * This file is part of the Swiftype App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swiftype\AppSearch\Exception;

/**
 * Exception thrown when trying to access a resource that does not exists.
 *
 * @package Swiftype\AppSearch\Exception
 *
 * @author  Aurélien FOUCRET <aurelien.foucret@elastic.co>
 */
class NotFoundException extends ApiException implements SwiftypeException
{
}
