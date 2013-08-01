<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Security\Authentication\Providers;

/**
 * Login object is a wrapper that holds the username and password submitted by current request.
 *
 * @package         Webiny\Component\Security\Authentication
 */

class Login
{

	/**
	 * @var string
	 */
	private $_username = '';
	/**
	 * @var string
	 */
	private $_password = '';
	/**
	 * @var bool
	 */
	private $_rememberMe = false;
	/**
	 * @var array
	 */
	private $_attributes = [];

	/**
	 * Base constructor.
	 *
	 * @param string $username   Username.
	 * @param string $password   Password.
	 * @param bool   $rememberMe Is rememberMe set or not.
	 */
	function __construct($username, $password, $rememberMe = false) {
		$this->_username = $username;
		$this->_password = $password;
		$this->_rememberMe = $rememberMe;
	}

	/**
	 * Sets an optional attribute into the current instance.
	 *
	 * @param string $name Attribute name.
	 * @param mixed $value Attribute value.
	 */
	function setAttribute($name, $value) {
		$this->_attributes[$name] = $value;
	}

	/**
	 * Returns the stored attribute for the defined $name.
	 *
	 * @param string $name Name of the attribute that you wish to return.
	 *
	 * @return null|mixed Null is returned if attribute doesn't exist, otherwise attribute value is returned.
	 */
	function getAttribute($name) {
		return isset($this->_attributes[$name]) ? $this->_attributes[$name] : null;
	}

	/**
	 * Returns the username.
	 *
	 * @return string
	 */
	function getUsername() {
		return $this->_username;
	}

	/**
	 * Returns the password.
	 *
	 * @return string
	 */
	function getPassword() {
		return $this->_password;
	}

	/**
	 * Return the status of remember me.
	 *
	 * @return bool
	 */
	function getRememberMe() {
		return $this->_rememberMe;
	}
}