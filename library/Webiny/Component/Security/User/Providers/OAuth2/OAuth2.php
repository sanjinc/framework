<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Security\User\Providers\OAuth2;

use Webiny\Component\Security\Authentication\Providers\Login;
use Webiny\Component\Security\User\Exceptions\UserNotFoundException;
use Webiny\Component\Security\User\UserAbstract;
use Webiny\Component\Security\User\UserProviderInterface;

/**
 * OAuth2 user provider
 *
 * @package         Webiny\Component\Security\User\Providers\OAuth2
 */

class OAuth2 implements UserProviderInterface
{

	/**
	 * Get the user from user provided for the given instance of Login object.
	 *
	 * @param Login $login Instance of Login object.
	 *
	 * @return UserAbstract
	 * @throws UserNotFoundException
	 */
	function getUser(Login $login) {
		// check if we have the oauth_server attribute
		if(!$login->getAttribute('oauth2_server')) {
			throw new UserNotFoundException('User not found.');
		}

		// try to get the user from oauth
		$oauth2 = $login->getAttribute('oauth2_server');
		try{
			$oauth2User = $oauth2->request()->getUserDetails();
		}catch (\Exception $e){
			$this->request()->session()->delete('oauth_token');
			throw new UserNotFoundException($e->getMessage());
		}

		// create the user object
		$user = new User();
		$user->populate($oauth2User->email, '', $login->getAttribute('oauth2_roles'), true);

		return $user;
	}
}