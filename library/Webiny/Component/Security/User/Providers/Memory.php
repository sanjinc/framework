<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Security\User\Providers;

use Webiny\Component\Security\User\UserAbstract;
use Webiny\Component\Security\User\UserProviderInterface;

/**
 * Description
 *
 * @package        Webiny\Component\Security\User\Providers
 */

class Memory extends UserAbstract implements UserProviderInterface
{

	/**
	 * Erase current authentication credentials.
	 * @return bool True if everything went ok.
	 */
	public function eraseCredentials() {
		// TODO: Implement eraseCredentials() method.
	}

	/**
	 * Authenticate user by $username and $password.
	 *
	 * @param string $username Username.
	 * @param string $password Password in raw format.
	 *
	 * @return bool|UserProviderInterface Instance of UserProviderInterface is returned if authentication was successful,
	 * otherwise false is returned
	 */
	static function authenticate($username, $password) {
		// TODO: Implement authenticate() method.
	}

	/**
	 * Returns a user for the given $username.
	 *
	 * @param string $username
	 *
	 * @return bool|UserProviderInterface Instance of UserProviderInterface is returned if a user is found,
	 * otherwise false is returned.
	 */
	static function loadByUsername($username) {
		// TODO: Implement loadByUsername() method.
	}
}