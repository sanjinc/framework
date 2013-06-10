<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Security\Token\Storage;

use Webiny\Component\Crypt\CryptTrait;
use Webiny\Component\Http\HttpTrait;
use Webiny\Component\Security\Token\TokenStorageAbstract;
use Webiny\Component\Security\User\UserAbstract;

/**
 * Session token provider.
 *
 * @package		 Webiny\Component\Security\User\Token\Storage
 */

class Cookie extends TokenStorageAbstract{

	use HttpTrait;

	/**
	 * Save user authentication token.
	 *
	 * @param UserAbstract $user
	 *
	 * @return bool
	 */
	function saveUserToken($user) {
		return $this->request()->cookie()->save($this->getTokenName(), $this->encryptUserData($user));
	}

	/**
	 * Check if auth token is present, if true, try to load the right user and return it's username.
	 *
	 * @return bool|UserAbstract False it user token is not available, otherwise the UserAbstract object is returned.
	 */
	function loadUserFromToken() {
		$token = $this->request()->cookie()->get($this->getTokenName());
		if(!$token){
			return false;
		}

		return $this->decryptUserData($token);
	}

	/**
	 * Deletes the current auth token.
	 *
	 * @return bool
	 */
	function deleteUserToken() {
		return $this->request()->cookie()->delete($this->getTokenName());
	}
}