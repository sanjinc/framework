<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Security\Authentication\Providers\TwitterOAuth;
require_once dirname(__FILE__).'/../../../../../../TwitterOAuth/twitteroauth/twitteroauth.php';

use Webiny\Component\Config\Config;
use Webiny\Component\Config\ConfigObject;
use Webiny\Component\Crypt\CryptTrait;
use Webiny\Component\Http\HttpTrait;
use Webiny\Component\Security\Authentication\Providers\AuthenticationInterface;
use Webiny\Component\Security\Authentication\Providers\Login;
use Webiny\Component\Security\Token\Token;
use Webiny\Component\Security\User\UserAbstract;
use Webiny\Component\StdLib\StdLibTrait;
use Webiny\WebinyTrait;

/**
 * TwitterOAuth authentication provider.
 *
 * @package         Webiny\Component\Security\Authentication\Providers\TwitterOAuth
 */

class TwitterOAuth implements AuthenticationInterface
{

	use WebinyTrait, StdLibTrait, CryptTrait, HttpTrait;

	/**
	 * @var array
	 */
	private $_oauthRoles = [];

	/**
	 * @var \TwitterOAuth
	 */
	private $_connection;

	/**
	 * @var Config
	 */
	private $_config;

	/**
	 * Base constructor.
	 *
	 * @param string       $serverName Name of the TwitterOAuth server in the current configuration.
	 * @param string|array $roles      Roles that will be set for the OAuth users.
	 *
	 * @throws TwitterOAuthException
	 */
	function __construct($serverName, $roles){
		try{
			$this->_config = self::webiny()->getConfig()->get('oauth2.'.$serverName);
			$this->_oauthRoles = (array) $roles;
		}catch (\Exception $e){
			throw new TwitterOAuthException($e->getMessage());
		}
	}

	/**
	 * This method is triggered on the login submit page where user credentials are submitted.
	 * On this page the provider should create a new Login object from those credentials, and return the object.
	 * This object will be then validated by user providers.
	 *
	 * @param ConfigObject $config Firewall config
	 *
	 * @throws TwitterOAuthException
	 * @return Login
	 */
	function getLoginObject($config) {
		// step1 -> get access token
		if(!$this->request()->session()->get('tw_oauth_token_secret', false)){
			$this->_connection = new \TwitterOAuth($this->_config->get('client_id'), $this->_config->get('client_secret'));

			// build redirect uri
			$redirectUri = $this->request()->getCurrentUrl(true)->setPath($this->_config->get('redirect_uri'));
			$requestToken = $this->_connection->getRequestToken($redirectUri);

			// save the session for later
			$this->request()->session()->save('tw_oauth_token', $requestToken['oauth_token']);
			$this->request()->session()->save('tw_oauth_token_secret', $requestToken['oauth_token_secret']);

			// check response code
			if($this->_connection->http_code==200){
				$authUrl =  $this->_connection->getAuthorizeURL($requestToken['oauth_token']);
				
				header('Location: '.$authUrl);
				die('Redirect');
			}else{
				throw new TwitterOAuthException('Could not connect to Twitter. Refresh the page or try again later.');
			}
		}else{
			$this->_connection = new \TwitterOAuth($this->_config->get('client_id'),
												   $this->_config->get('client_secret'),
												   $this->request()->session()->get('tw_oauth_token'),
												   $this->request()->session()->get('tw_oauth_token_secret'));

			// request access tokens from twitter
			if($this->request()->query('oauth_verifier', false)){
				$access_token = $this->_connection->getAccessToken($this->request()->query('oauth_verifier'));
			}else{
				// remove no longer needed request tokens
				$this->request()->session()->delete('tw_oauth_token');
				$this->request()->session()->delete('tw_oauth_token_secret');

				// redirect back to login
				$this->request()->redirect($this->request()->getCurrentUrl());
			}

			// error check
			if($this->_connection->http_code!=200){
				throw new TwitterOAuthException('Could not connect to Twitter. Refresh the page or try again later.');
			}

			// save the access tokens. Normally these would be saved in a database for future use.
			$this->request()->session()->save('tw_access_token', $access_token);

			// remove no longer needed request tokens
			$this->request()->session()->delete('tw_oauth_token');
			$this->request()->session()->delete('tw_oauth_token_secret');
		}

		// step2 -> return the login object with auth token
		$login = new Login('', '');
		$login->setAttribute('tw_oauth_server', $this->_connection);
		$login->setAttribute('tw_oauth_roles', $this->_oauthRoles);

		return $login;
	}

	/**
	 * This callback is triggered after we validate the given login data from getLoginObject, and the data IS NOT valid.
	 * Use this callback to clear the submit data from the previous request so that you don't get stuck in an
	 * infinitive loop between login page and login submit page.
	 */
	function invalidLoginProvidedCallback() {
		// TODO: Implement invalidLoginProvidedCallback() method.
	}

	/**
	 * This callback is triggered after we have validated user credentials and have created a user auth token.
	 *
	 * @param UserAbstract $user
	 */
	function loginSuccessfulCallback(UserAbstract $user) {
		// TODO: Implement loginSuccessfulCallback() method.
	}

	/**
	 * This callback is triggered when the system has managed to retrieve the user from the stored token (either session)
	 * or cookie.
	 *
	 * @param UserAbstract $user
	 * @param Token        $token
	 *
	 * @return mixed
	 */
	function userAuthorizedByTokenCallback(UserAbstract $user, Token $token) {
		// TODO: Implement userAuthorizedByTokenCallback() method.
	}

	/**
	 * Logout callback is called when user auth token was deleted.
	 */
	function logoutCallback() {
		// TODO: Implement logoutCallback() method.
	}
}