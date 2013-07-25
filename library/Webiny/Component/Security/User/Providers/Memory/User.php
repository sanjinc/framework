<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Security\User\Providers\Memory;

use Webiny\Component\Security\Authentication\Providers\Login;
use Webiny\Component\Security\Encoder\Encoder;
use Webiny\Component\Security\User\UserAbstract;

/**
 * Memory user provider - user class
 *
 * @package         Webiny\Component\Security\User\Providers\Memory
 */

class User extends UserAbstract
{
	/**
	 * This method verifies the credentials of current user with the credentials provided from the Login object.
	 *
	 * @param Login   $login
	 * @param Encoder $encoder
	 *
	 * @throws MemoryException
	 * @return bool Return true if credentials are valid, otherwise return false.
	 */
	function authenticate(Login $login, Encoder $encoder) {
		try{
			$result = $encoder->verifyPasswordHash($login->getPassword(), $this->getPassword());
		}catch (\Exception $e){
			throw new MemoryException($e->getMessage());
		}

		return $result;
	}
}