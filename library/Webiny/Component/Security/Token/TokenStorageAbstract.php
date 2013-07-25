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

/**
 * Token storage abstract.
 *
 * @package         Webiny\Component\Security\User\Token
 */

abstract class TokenStorageAbstract implements TokenStorageInterface
{
	use HttpTrait, CryptTrait;

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
	 * @param UserAbstract $user             Instance of UserAbstract class that holds the pre-filled object from user provider.
	 *
	 * @return string
	 */
	function encryptUserData(UserAbstract $user) {
		// data (we use short syntax to reduce the size of the cookie or session)
		$data = [
			// username
			'u'  => $user->getUsername(),
			// rules
			'r'  => $user->getRoles(),
			// valid until
			'vu' => time() + (86400 * 30),
		];

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
		try {
			// decrypt token data
			$data = $this->crypt()->decrypt($tokenData, $this->_encKey);
			$data = unserialize($data);

			// validate token data
			if(!isset($data['u']) || !isset($data['r']) || !isset($data['vu']))
			{
				$this->deleteUserToken();
				return false;
			}

			// create token data instance
			$tokenData = new TokenData($data);

			// check that token data is still valid
			if(!$tokenData->isValid()){
				$this->deleteUserToken();

				return false;
			}

			return $tokenData;
		} catch (Exception $e) {
			// delete the token before we throw the exception
			$this->deleteUserToken();

			throw new TokenException($e->getMessage());
		}
	}
}