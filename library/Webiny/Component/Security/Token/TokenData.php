<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Security\Token;

use Webiny\StdLib\StdLibTrait;

/**
 * Token data class holds user data that has been decrypted from token storrage.
 *
 * @package         Webiny\Component\Security\Token
 */

class TokenData
{
	use StdLibTrait;

	private $_username;
	private $_roles;
	private $_validUntil;

	/**
	 * @param array $tokenData Decrypted token data array.
	 */
	function __construct(array $tokenData){
		$this->_username = $tokenData['u'];
		$this->_roles = $tokenData['r'];
		$this->_validUntil = $tokenData['vu'];
	}

	/**
	 * Returns the username stored in token data.
	 *
	 * @return string
	 */
	function getUsername(){
		return $this->_username;
	}

	/**
	 * Returns the roles stored in token data.
	 *
	 * @return array
	 */
	function getRoles(){
		return $this->_roles;
	}

	/**
	 * Checks if the token is still valid or if it has expired.
	 *
	 * @return bool True if token is still valid.
	 */
	function isValid(){
		return !$this->datetime()->setTimestamp($this->_validUntil)->isPast();
	}
}