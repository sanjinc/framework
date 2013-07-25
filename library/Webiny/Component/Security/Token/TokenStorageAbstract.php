<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Security\Token;

use Webiny\Component\Crypt\CryptTrait;
use Webiny\Component\Http\HttpTrait;
use Webiny\Component\Security\Token\TokenStorageInterface;
use Webiny\Component\Security\User\UserAbstract;
use Webiny\StdLib\Exception\Exception;
use Webiny\StdLib\StdLibTrait;

/**
 * Token storage abstract.
 *
 * @package         Webiny\Component\Security\User\Token
 */

abstract class TokenStorageAbstract implements TokenStorageInterface
{
	use HttpTrait, CryptTrait, StdLibTrait;

	private $_tokenName;
	private $_encKey = 'WF-AUTH-KEY-SECU';

	/**
	 * This function provides the token name to the storage.
	 *
	 * @param string $tokenName Token name.
	 */
	function setTokenName($tokenName) {
		$this->_tokenName = $tokenName;
	}

	/**
	 * Get token name.
	 *
	 * @return string Token name.
	 */
	function getTokenName() {
		return $this->_tokenName;
	}

	/**
	 * Stores user data into an array, encrypts it and returns the encrypted string.
	 *
	 * @param UserAbstract $user Instance of UserAbstract class that holds the pre-filled object from user provider.
	 *
	 * @return string
	 */
	function encryptUserData(UserAbstract $user) {
		// data (we use short syntax to reduce the size of the cookie or session)
		$data = [
			// username
			'u'   => $user->getUsername(),
			// rules
			'r'   => $user->getRoles(),
			// valid until
			'vu'  => time() + (86400 * 30),
			// sid
			'sid' => $this->request()->session()->getSessionId()
		];

		// build and add token to $data
		$token = $this->str($data['u'], '|' . $data['vu'] . '|' . $this->_encKey)->hash()->val();
		$data['t'] = $token;

		return $this->crypt()->encrypt(serialize($data), $this->_encKey);
	}

	/**
	 * Decrypts the provided $tokenData, unserializes the string, creates an instance of TokenData and validates it.
	 * If TokenData is valid, its instance is returned, otherwise false is returned.
	 *
	 * @param string $tokenData Encrypted data.
	 *
	 * @return TokenData|bool
	 * @throws TokenException
	 */
	function decryptUserData($tokenData) {
		// decrypt token data
		try {
			$data = $this->crypt()->decrypt($tokenData, $this->_encKey);
			$data = unserialize($data);
		} catch (\Exception $e) {
			$this->deleteUserToken();

			return false;
		}

		// validate token data
		if(!isset($data['u'])
			|| !isset($data['r'])
			|| !isset($data['vu'])
			|| !isset($data['sid'])
			|| !isset($data['t'])
		) {
			$this->deleteUserToken();

			return false;
		}

		// validate sid so we are sure that nobody stole a cookie
		if($this->request()->session()->getSessionId() != $data['sid']) {
			$this->deleteUserToken();

			return false;
		}

		// validate token-token :)
		$token = $this->str($data['u'], '|' . $data['vu'] . '|' . $this->_encKey)->hash()->val();
		if($token != $data['t']) {
			$this->deleteUserToken();

			return false;
		}

		// check that token data is still valid
		if($this->datetime()->setTimestamp($data['vu'])->isPast()) {
			$this->deleteUserToken();

			return false;
		}

		// return TokenData instance
		return new TokenData($data);
	}
}