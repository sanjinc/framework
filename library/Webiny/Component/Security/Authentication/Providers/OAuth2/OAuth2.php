<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Security\Authentication\Providers\OAuth2;

use Webiny\Component\Crypt\CryptTrait;
use Webiny\Component\Http\HttpTrait;
use Webiny\Component\OAuth2\OAuth2Loader;
use Webiny\Component\Security\Authentication\Providers\AuthenticationInterface;
use Webiny\Component\Security\Authentication\Providers\Login;
use Webiny\Component\Security\User\UserAbstract;
use Webiny\Component\Security\Token\Token;
use Webiny\Component\Config\ConfigObject;
use Webiny\StdLib\StdLibTrait;
use Webiny\WebinyTrait;

/**
 * OAuth2 authentication provider.
 *
 * @package		 Webiny\Component\Security\Authentication\Providers
 */
 
class OAuth2 implements AuthenticationInterface{

	use StdLibTrait, HttpTrait, WebinyTrait, CryptTrait;

	/**
	 * @var null|\Webiny\Component\OAuth2\OAuth2
	 */
	private $_oauth2Instance = null;
	/**
	 * @var array
	 */
	private $_oauth2Roles = [];


	/**
	 * Base constructor.
	 *
	 * @param string       $serverName Name of the OAuth2 server in the current configuration.
	 * @param string|array $roles      Roles that will be set for the OAuth2 users.
	 *
	 * @throws OAuth2Exception
	 */
	function __construct($serverName, $roles){
		try{
			$this->_oauth2Instance = OAuth2Loader::getInstance($serverName);
			$this->_oauth2Roles = (array) $roles;
		}catch (\Exception $e){
			throw new OAuth2Exception($e->getMessage());
		}
	}
	
	/**
	 * This method is triggered on the login submit page where user credentials are submitted.
	 * On this page the provider should create a new Login object from those credentials, and return the object.
	 * This object will be then validated by user providers.
	 *
	 * @param ConfigObject $config Firewall config
	 *
	 * @throws OAuth2Exception
	 * @return Login
	 */
	function getLoginObject($config) {
		// step1 -> get access token
		$oauth2 = $this->_getOAuth2Instance($config);
		if(!$this->request()->query('code', false)){
			$this->request()->session()->delete('oauth_token');

			// append state param to make the request more secured
			$state = $this->_createOAuth2State();
			$this->request()->session()->save('oauth_state', $state);
			$oauth2->setState($state);

			$oauth2 = $this->_getOAuth2Instance();
			$authUrl = $oauth2->getAuthenticationUrl();

			header('Location: ' . $authUrl);
			die('Redirect');
		}else if(!$this->request()->session()->get('oauth_token', false)){
			$accessToken = $oauth2->requestAccessToken();
			$this->request()->session()->save('oauth_token', $accessToken);
		}else{
			$accessToken = $this->request()->session()->get('oauth_token', false);
		}

		// verify oauth state
		$oauthState = $this->request()->query('state', '');
		$state = $this->request()->session()->get('oauth_state', 'invalid');
		if($oauthState!=$state){
			throw new OAuth2Exception('The state parameter from OAuth2 response doens\'t match the users state parameter.');
		}

		$oauth2->setAccessToken($accessToken);

		if($this->isArray($accessToken) && isset($accessToken['result']['error']))
		{
			$this->request()->session()->delete('oauth_token');
			$this->_redirectToLoginPage($config);
		}

		// step2 -> return the login object with auth token
		$login = new Login('', '');
		$login->setAttribute('oauth2_server', $oauth2);
		$login->setAttribute('oauth2_roles', $this->_oauth2Roles);

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


	/**
	 * @return array|null|\Webiny\Component\OAuth2\OAuth2
	 */
	private function _getOAuth2Instance(){
		return $this->_oauth2Instance;
	}

	/**
	 * Redirect the user back to login page.
	 *
	 * @param ConfigObject $config
	 */
	private function _redirectToLoginPage($config){
		$loginPath = $config->login->path;
		$redirectUri = $this->url($loginPath);

		$this->request()->redirect($redirectUri->val());
		die();
	}

	private function _createOAuth2State(){
		$state = $this->crypt()->generateUserReadableString(10);

		return $state;
	}
}