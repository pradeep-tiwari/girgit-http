<?php

/**
 * @package Girgit
 * @license MIT
 * @author Pradeep T. <pt21388@gmail.com>
 * @copyright Copyright (c) 2016, Pradeep T.
 *
 * This file is part of the Girgit package. For the full copyright and license
 * information, please view the LICENSE file that was distributed with this source
 * code.
 */

namespace Girgit\Http;

use Exception;

/**
 * This class extends built-in \Exception class to provide custom exceptions when
 * a non-existent property of a class is called.
 */
class BadPropertyCallException extends Exception {
    // nothing to do here
}