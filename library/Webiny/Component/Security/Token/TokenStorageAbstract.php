<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Security\Token;

use Webiny\Component\Crypt\Crypt;
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

	function encryptUserData(UserAbstract $user) {
		$data = [
			'username' => $user->getUsername(),
			'roles'    => $user->getRoles(),
			'time'     => time(),
			'provider' => $user->getProviderName()
		];

		return $this->crypt()->encrypt(serialize($data), 'WF-AUTH-KEY');
	}

	function decryptUserData($tokenData) {
		try {
			// decrypt token data
			$userData = $this->crypt()->decrypt($tokenData, 'WF-AUTH-KEY');
			$userData = unserialize($userData);

			// build the user object
			$user = new $userData['provider']();
			$user->populate($userData['username'], '', $userData['roles'], false);
		} catch (Exception $e) {
			// delete the token before we throw the exception

			$this->deleteUserToken();

			throw new TokenException($e->getMessage());
		}
	}
}