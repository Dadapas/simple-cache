<?php
namespace Dadapas\Cache;

/**
 * This file is part of the dadapas/cache library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) TOVOHERY Z. Pascal <tovoherypascal@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 */

use Exception;
use Psr\Cache\InvalidArgumentException as InvalidArgumentExceptionIterface;

class InvalidArgumentException extends Exception implements InvalidArgumentExceptionIterface {}