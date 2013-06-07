<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Security\User;

use Webiny\Component\Security\Encoder\EncoderDriverInterface;
use Webiny\Component\Security\User\Exceptions\UserNotFoundException;

/**
 * Description
 *
 * @package         Webiny\Component\Security\User
 */

interface UserProviderInterface
{

	/**
	 * Get the user from user provided for the given $username.
	 *
	 * @param string $username Username
	 *
	 * @return UserAbstract
	 * @throws UserNotFoundException
	 */
	function getUserByUsername($username);
}
