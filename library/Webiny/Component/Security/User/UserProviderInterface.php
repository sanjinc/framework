<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Security\User;

/**
 * Description
 *
 * @package         Webiny\Component\Security\User
 */

interface UserProviderInterface
{

	/**
	 * @return string Username.
	 */
	public function getUsername();

	/**
	 * @return string Hashed password.
	 */
	public function getPassword();

	/**
	 * Get a list of assigned roles
	 * @return array List of assigned roles.
	 */
	public function getRoles();

	/**
	 * Check if user is already authenticated.
	 *
	 * @return bool True if user is authenticated, otherwise false.
	 */
	public function isAuthenticated();

	/**
	 * Erase current authentication credentials.
	 * @return bool True if everything went ok.
	 */
	public function eraseCredentials();

	/**
	 * Authenticate user by $username and $password.
	 *
	 * @param string $username Username.
	 * @param string $password Password in raw format.
	 *
	 * @return bool|UserProviderInterface Instance of UserProviderInterface is returned if authentication was successful,
	 * otherwise false is returned
	 */
	static function authenticate($username, $password);

	/**
	 * Returns a user for the given $username.
	 *
	 * @param string $username
	 *
	 * @return bool|UserProviderInterface Instance of UserProviderInterface is returned if a user is found,
	 * otherwise false is returned.
	 */
	static function loadByUsername($username);

}
