<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\OAuth2\Server;

use OAuth2\Exception;
use Webiny\Component\OAuth2\OAuth2User;
use Webiny\Component\OAuth2\ServerAbstract;
use Webiny\StdLib\StdLibTrait;

/**
 * Facebook OAuth2 wrapper for the Graph API.
 *
 * @package         Webiny\Component\OA2W
 */

class Facebook extends ServerAbstract
{
	use StdLibTrait;

	/**
	 * @link https://developers.facebook.com/docs/reference/api/user/
	 */
	const API_ME = 'https://graph.facebook.com/me';

	/**
	 * Returns an array [url, params].
	 * 'url' - holds the destination url for accessing user details on the OAuth2 server.
	 * 'params' - an optional array of additional parameters that would be sent together with the request.
	 *
	 * @return array
	 */
	protected function _getUserDetailsTargetData() {
		return [
			'url'    => self::API_ME,
			'params' => []
		];
	}

	/**
	 * This method is called automatically when the OAuth2 server returns a response containing user details.
	 * The method should process the response an return and instance of OAuth2User.
	 *
	 * @param array $result OAuth2 server response.
	 *
	 * @return OAuth2User
	 * @throws \OAuth2\Exception
	 */
	function _processUserDetails($result){
		$result = self::arr($result['result']);
		if($result->keyExists('error')){
			throw new Exception($result->key('error')['message']);
		}

		$user = new OAuth2User($result->key('username'), $result->key('email', '', true));
		$user->setProfileId($result->key('id'));
		$user->setFirstName($result->key('first_name'));
		$user->setLastName($result->key('last_name'));
		$user->setProfileUrl($result->key('link'));
		$user->setAvatarUrl('http://graph.facebook.com/'.$user->profileId.'/picture?type=large');
		$user->setLastUpdateTime(strtotime($result->key('updated_time')));

		return $user;
	}
}