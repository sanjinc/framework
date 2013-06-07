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
 * This is the abstract user class with common helpers functions for UserProviders.
 * You can optionally extend this class if you want to inherit the common getter functions.
 *
 * @package         Webiny\Component\Security\User
 */

abstract class UserAbstract implements UserInterface
{

	private $_username = '';
	private $_password = '';
	private $_isAuthenticated = false;
	private $_roles = [];

	/**
	 * Populate the user object.
	 *
	 * @param string $username        Username.
	 * @param string $password        Hashed password.
	 * @param array  $roles           Array of assigned roles.
	 * @param bool   $isAuthenticated Boolean flag that tells us if user is already authenticated or not.
	 */
	function populate($username, $password, array $roles, $isAuthenticated = false) {
		$this->_username = $username;
		$this->_password = $password;
		$this->_roles = $roles;
		$this->_isAuthenticated = $isAuthenticated;
	}

	/**
	 * @return string Username.
	 */
	public function getUsername() {
		return $this->_username;
	}

	/**
	 * @return string Hashed password.
	 */
	public function getPassword() {
		return $this->_password;
	}

	/**
	 * Get a list of assigned roles
	 * @return array List of assigned roles.
	 */
	public function getRoles() {
		return $this->_roles;
	}

	/**
	 * Check if current user has the defined role.
	 *
	 * @param string $role Role name
	 *
	 * @return bool True if user has the role, otherwise false.
	 */
	public function hasRole($role){
		return in_array($role, $this->getRoles(), false);
	}

	/**
	 * Check if user is already authenticated.
	 *
	 * @return bool True if user is authenticated, otherwise false.
	 */
	public function isAuthenticated() {
		return $this->_isAuthenticated;
	}

	function setIsAuthenticated($bool){
		$this->_isAuthenticated = $bool;
	}
}