<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Security\User;

use Webiny\Component\Security\Role\Role;
use Webiny\Component\Security\Token\TokenData;
use Webiny\StdLib\StdLibTrait;
use Webiny\StdLib\StdObject\ArrayObject\ArrayObject;

/**
 * This is the abstract user class with common helpers functions for UserProviders.
 * You can optionally extend this class if you want to inherit the common getter functions.
 *
 * @package         Webiny\Component\Security\User
 */

abstract class UserAbstract implements UserInterface
{
	use StdLibTrait;

	/**
	 * @var string Users username.
	 */
	private $_username = '';

	/**
	 * @var string Users password.
	 */
	private $_password = '';

	/**
	 * @var bool Is user authenticated flag.
	 */
	private $_isAuthenticated = false;

	/**
	 * @var ArrayObject An list of user roles.
	 */
	private $_roles;

	/**
	 * Populate the user object.
	 *
	 * @param string $username        Username.
	 * @param string $password        Hashed password.
	 * @param array  $roles           Array of the assigned roles.
	 * @param bool   $isAuthenticated Boolean flag that tells us if user is already authenticated or not.
	 */
	function populate($username, $password, array $roles, $isAuthenticated = false) {
		// store general data
		$this->_username = $username;
		$this->_password = $password;
		$this->_isAuthenticated = $isAuthenticated;

		$this->_roles = $this->arr([]);
		foreach($roles as $r){
			$this->_roles->append(new Role($r));
		}
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
		return $this->_roles->val();
	}

	/**
	 * Check if current user has the defined role.
	 *
	 * @param string $role Role name
	 *
	 * @return bool True if user has the role, otherwise false.
	 */
	public function hasRole($role) {
		return $this->_roles->keyExists($role, false);
	}

	/**
	 * Check if user is already authenticated.
	 *
	 * @return bool True if user is authenticated, otherwise false.
	 */
	public function isAuthenticated() {
		return $this->_isAuthenticated;
	}

	/**
	 * Sets the auth flag.
	 *
	 * @param $bool
	 */
	public function setIsAuthenticated($bool) {
		$this->_isAuthenticated = $bool;
	}

	/**
	 * This method compares the $tokenData against the current user and returns true if users are identical,
	 * otherwise false is returned.
	 *
	 * @param TokenData $tokenData
	 *
	 * @return bool
	 */
	public function isTokenValid(TokenData $tokenData) {
		$roles = [];
		foreach($this->_roles as $role){
			$roles[] = $role->getRole();
		}
		$currentUser = $this->str($this->getUsername() . implode(',', $roles))->hash('md5');

		$tokenRoles = [];
		foreach($tokenData->getRoles() as $role){
			$tokenRoles[] = $role->getRole();
		}
		$tokenUser = $this->str($tokenData->getUsername() . implode(',', $tokenRoles))->hash('md5');

		return ($currentUser == $tokenUser);
	}

	/**
	 * Sets the user roles.
	 *
	 * @param array $roles An array of Role instances.
	 */
	public function setRoles(array $roles){
		$this->_roles = $this->arr($roles);
	}
}