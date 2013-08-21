<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Security\Token;

use Webiny\Component\StdLib\StdLibTrait;

/**
 * Token data class holds user data that has been decrypted from token storage.
 *
 * @package         Webiny\Component\Security\Token
 */

class TokenData
{
	use StdLibTrait;

	/**
	 * Users username.
	 * @var string
	 */
	private $_username;

	/**
	 * Array of roles.
	 * @var array
	 */
	private $_roles;

	/**
	 * Timestamp until the token data is valid.
	 * @var int
	 */
	private $_validUntil;

	/**
	 * Name of the class that was used to provide the user authentication.
	 * @var string
	 */
	private $_authProviderDriver;

	/**
	 * Base constructor.
	 *
	 * @param array $tokenData Decrypted token data array.
	 */
	function __construct(array $tokenData) {
		$this->_username = $tokenData['u'];
		$this->_roles = $tokenData['r'];
		$this->_validUntil = $tokenData['vu'];
		$this->_authProviderDriver = $tokenData['ap'];
	}

	/**
	 * Returns the username stored in token data.
	 *
	 * @return string
	 */
	function getUsername() {
		return $this->_username;
	}

	/**
	 * Returns the roles stored in token data.
	 *
	 * @return array
	 */
	function getRoles() {
		return $this->_roles;
	}

	/**
	 * Returns the name of auth provider driver.
	 *
	 * @return string
	 */
	function getAuthProviderDriver() {
		return $this->_authProviderDriver;
	}
}