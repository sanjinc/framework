<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Security\User\Exceptions;

use Webiny\Component\StdLib\Exception\ExceptionAbstract;

/**
 * User not found exception.
 * This exception is thrown by UserProvider when a user is not found.
 *
 * @package		 Webiny\Component\Security\User\Exceptions
 */
 
class UserNotFoundException extends ExceptionAbstract{}