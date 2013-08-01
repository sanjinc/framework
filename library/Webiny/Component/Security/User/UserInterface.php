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
 * User interface.
 * Every user provider User class must implement this interface.
 *
 * @package         Webiny\Component\Security\User
 */

interface UserInterface
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
}