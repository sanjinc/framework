<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Security\Token;

use Webiny\Component\Security\User\UserAbstract;
use Webiny\StdLib\Exception\Exception;
use Webiny\StdLib\FactoryLoaderTrait;
use Webiny\WebinyTrait;

/**
 * Token abstract.
 *
 * @package         Webiny\Component\Security\User\Token
 */

class Token
{
	use WebinyTrait, FactoryLoaderTrait;

	// store token into cookie -> only if remember me is TRUE
	const TOKEN_COOKIE_STORAGE = '\Webiny\Component\Security\Token\Storage\Cookie';
	// store token into session -> only if remember me is FALSE
	const TOKEN_SESSION_STORAGE = '\Webiny\Component\Security\Token\Storage\Cookie';

	/**
	 * @var TokenStorageAbstract
	 */
	private $_storage;

	/**
	 * @var bool
	 */
	private $_rememberMe = false;

	/**
	 * Base constructor.
	 *
	 * @param string $tokenName   Name of the token.
	 * @param bool   $rememberMe  Do you want to store the token into cookie, or not. If you don't store it into cookie, the
	 *                            token is only valid for current session.
	 * @param string $securityKey Security key that will be used for encryption of token data
	 *
	 * @throws TokenException
	 */
	function __construct($tokenName, $rememberMe = false, $securityKey) {

		$this->_rememberMe = $rememberMe;

		try {
			$this->_storage = $this->factory($this->_getStorageName(),
											 '\Webiny\Component\Security\Token\TokenStorageAbstract');
			$this->_storage->setSecurityKey($securityKey);
		} catch (Exception $e) {
			throw new TokenException($e->getMessage());
		}

		$this->_storage->setTokenName($tokenName);
	}

	/**
	 * Returns the correct storage name. If 'rememberMe' is true, Cookie storage is returned, otherwise
	 * Session storage is returned.
	 *
	 * @return string
	 */
	private function _getStorageName() {
		if($this->_rememberMe) {
			return self::TOKEN_COOKIE_STORAGE;
		}

		return self::TOKEN_SESSION_STORAGE;
	}

	/**
	 * Tries to load current user from token and if succeeds, an instance of TokenData is returned.
	 *
	 * @return bool|TokenData Instance of TokenData is returned is the token exists, otherwise false is returned.
	 */
	function getUserFromToken() {
		return $this->_storage->loadUserFromToken();
	}

	/**
	 * Creates a token for the given $user.
	 *
	 * @param UserAbstract $user             Instance of UserAbstract class that holds the pre-filled object from user provider.
	 *
	 * @return bool
	 */
	function saveUser(UserAbstract $user) {
		return $this->_storage->saveUserToken($user);
	}

	/**
	 * Deletes current token.
	 *
	 * @return bool
	 */
	function deleteUserToken() {
		return $this->_storage->deleteUserToken();
	}
}