<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Security\User\Providers\TwitterOAuth;

use Webiny\Component\Http\HttpTrait;
use Webiny\Component\OAuth2\OAuth2User;
use Webiny\Component\Security\Authentication\Providers\Login;
use Webiny\Component\Security\User\Exceptions\UserNotFoundException;
use Webiny\Component\Security\User\UserAbstract;
use Webiny\Component\Security\User\UserProviderInterface;

/**
 * TwitterOAuth user provider
 *
 * @package         Webiny\Component\Security\User\Providers\OAuth2
 */

class TwitterOAuth implements UserProviderInterface
{
	use HttpTrait;

	/**
	 * Get the user from user provided for the given instance of Login object.
	 *
	 * @param Login $login Instance of Login object.
	 *
	 * @return UserAbstract
	 * @throws UserNotFoundException
	 */
	function getUser(Login $login) {
		// check if we have the tw_oauth_server attribute
		if(!$login->getAttribute('tw_oauth_server')) {
			throw new UserNotFoundException('User not found.');
		}

		// try to get the user from oauth
		$connection = $login->getAttribute('tw_oauth_server');
		try{
			$twUser = $connection->get('account/verify_credentials');

			/**
			 * We return the OAuth2User with the hope that twitter will some day implement OAuth2 protocol
			 * (how naive of us)
			 */
			$oauth2User = new OAuth2User($twUser->screen_name, $twUser->screen_name);
			$oauth2User->setAvatarUrl($twUser->profile_image_url);
			$oauth2User->setServiceName('twitter');
			$oauth2User->setFirstName($twUser->name);
			$oauth2User->setLastName('');

			// hack for Weby.io
			$this->request()->session()->save('oauth2_user', $oauth2User);
		}catch (\Exception $e){
			throw new UserNotFoundException($e->getMessage());
		}

		// create the user object
		$user = new User();
		$user->populate($twUser->screen_name, '', $login->getAttribute('tw_oauth_roles'), true);

		return $user;
	}
}