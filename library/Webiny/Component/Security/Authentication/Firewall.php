<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Security\Authentication;

use Webiny\Component\Config\Config;
use Webiny\Component\Config\ConfigObject;
use Webiny\Component\Http\HttpTrait;
use Webiny\Component\Security\Authentication\Providers\AuthenticationInterface;
use Webiny\Component\Security\Authentication\Providers\Login;
use Webiny\Component\Security\Encoder\Encoder;
use Webiny\Component\Security\User\AnonymousUser;
use Webiny\Component\Security\User\Exceptions\UserNotFoundException;
use Webiny\Component\Security\User\Providers\Memory;
use Webiny\Component\Security\Token\Token;
use Webiny\Component\Security\User\User;
use Webiny\Component\Security\User\UserAbstract;
use Webiny\StdLib\Exception\Exception;
use Webiny\StdLib\FactoryLoaderTrait;
use Webiny\StdLib\SingletonTrait;
use Webiny\StdLib\StdLibTrait;

/**
 * This is the main class for authentication layer.
 * The firewall class check if users is authenticated and holds the methods for authentication.
 *
 * @package         Webiny\Component\Security\Authentication
 */

class Firewall
{

	use HttpTrait, StdLibTrait, FactoryLoaderTrait;

	/**
	 * @var \Webiny\Component\Config\ConfigObject
	 */
	private $_config;

	/**
	 * @var array An array of user provider instances.
	 */
	private $_userProviders = [];

	/**
	 * @var string Name of the current firewall.
	 */
	private $_firewallKey;

	/**
	 * @var \Webiny\Component\Security\Encoder\Encoder
	 */
	private $_encoder;

	/**
	 * @var Token
	 */
	private $_token;

	/**
	 * @var bool|UserAbstract
	 */
	private $_user = false;

	/**
	 * @var AuthenticationInterface
	 */
	private $_authProvider;

	/**
	 * Constructor.
	 *
	 * @param string       $firewallKey    Name of the current firewall.
	 * @param ConfigObject $firewallConfig Firewall config.
	 * @param array        $userProviders  Array of user providers for this firewall.
	 * @param Encoder      $encoder        Instance of encoder for this firewall.
	 */
	function __construct($firewallKey, ConfigObject $firewallConfig, array $userProviders, Encoder $encoder) {
		$this->_firewallKey = $firewallKey;
		$this->_config = $firewallConfig;
		$this->_userProviders = $userProviders;
		$this->_encoder = $encoder;
	}

	/**
	 * This method tries to initialize the firewall.
	 * If firewall doesn't match its url pattern, false is returned, otherwise authentication process is triggered.
	 * If user is authenticated UserAbstract is returned, otherwise the firewall will redirect the user to the login page.
	 *
	 * @return bool|UserAbstract
	 */
	function init() {
		// setup authorization layer
		if(!$this->_setupAuthLayer()) {
			return false;
		}

		// init token
		$this->_initToken();

		// before anything else, let's check if we are on the logout page
		if($this->_isLogoutPage()){
			$this->processLogout();
		}

		// get user
		return $this->getUser();
	}

	/**
	 * This method is triggered on the request that requires an authenticated user, but the current user in not
	 * authenticated.
	 *
	 * @return bool|UserAbstract Upon valid authentication an instance of UserAbstract is returned, otherwise false is returned.
	 */
	public function setupAuth(){
		if($this->_isLoginPage()) {
			$this->_getAuthProvider()->triggerLogin($this->getConfig());
			// if we enter login page, the user is Anonymous
			return new AnonymousUser();
		}else if($this->_isLoginSubmitPage()){
			$user = $this->_validateLoginPageSubmit();
			if($user){
				return $user;
			}
		}

		$this->request()->redirect($this->request()->getCurrentUrl(true)
									   ->setPath($this->getConfig()->login->path));
	}

	/**
	 * This method deletes user auth token and calls the logoutCallback on current login provider.
	 * After that, it replaces the current user instance with an instance of AnonymousUser and redirects the request to
	 * the logout.target.
	 */
	function processLogout(){
		$this->getToken()->deleteUserToken();
		$this->_getAuthProvider()->logoutCallback();
		$this->_user = new AnonymousUser();

		$this->request()->redirect($this->request()->getCurrentUrl(true)->setPath($this->getConfig()->logout->target), 401);
	}

	/**
	 * Checks if current request matches the login submit page. If true, auth provider is initialized and submitted
	 * credentials are wrapped into a Login object.
	 * Once we have the login object, method calls the authentication method to validate the credentials.
	 * If credentials are valid, an instance of UserAbstract is returned, otherwise false.
	 *
	 * @return bool|UserAbstract
	 * @throws FirewallException
	 */
	private function _validateLoginPageSubmit() {
		if($this->_isLoginSubmitPage()) {
			// get the login object
			try {
				$login = $this->_getAuthProvider()->getLoginObject($this->getConfig());
				if(!$this->isInstanceOf($login, 'Webiny\Component\Security\Authentication\Providers\Login')) {
					throw new FirewallException('Login provider must return an instance of
														"Webiny\Component\Security\Authentication\Providers\Login".');
				}
			} catch (\Exception $e) {
				throw new FirewallException($e->getMessage());
			}

			// forward the login object to user providers and validate the credentials
			if(!($user = $this->_authenticate($login))) { // login failed
				$this->_getAuthProvider()->invalidLoginProvidedCallback();

				return false;
			} else {
				$this->_getAuthProvider()->loginSuccessfulCallback($user);

				return $user;
			}
		}

		return false;
	}

	/**
	 * Tries to retrieve the user from current token.
	 * If the token does not exist, AnonymousUser is returned.
	 *
	 * @throws FirewallException
	 * @return bool|\Webiny\Component\Security\User\UserAbstract
	 */
	function getUser() {
		try {
			// get token
			$this->_user = new AnonymousUser();
			$tokenData = $this->getToken()->getUserFromToken();
			if(!$tokenData){
				return $this->_user;
			}else{
				// try to get user object from user providers
				$user = $this->_getUserFromUserProvider($tokenData->getUsername());

				// check if user object from the provider matches the object from token
				if($user->isTokenValid($tokenData)){
					$this->_user = $user;
				}else{
					$this->processLogout();
				}
			}

			return $this->_user;
		} catch (\Exception $e) {
			throw new FirewallException($e->getMessage());
		}
	}

	/**
	 * Get realm name.
	 *
	 * @return string Realm name.
	 */
	function getRealmName() {
		return $this->_config->realm_name;
	}

	/**
	 * Get url pattern defined for current firewall.
	 *
	 * @return string. Url pattern.
	 */
	function getUrlPattern() {
		return $this->_config->url_pattern;
	}

	/**
	 * Check if anonymous access is allowed or not.
	 * If anonymous access is not defined in the config, by default it will be set to false.
	 *
	 * @return bool Is anonymous access allowed or not.
	 */
	function getAnonymousAccess() {
		return $this->_config->get('anonymous', false);
	}

	/**
	 * Get config for current firewall.
	 *
	 * @return ConfigObject
	 */
	function getConfig() {
		return $this->_config;
	}

	/**
	 * Checks if the auth layer should be installed for current request.
	 * If we cannot match the current url using the pattern from the config, auth layer will not be installed.
	 *
	 * @return bool|\Webiny\StdLib\StdObject\ArrayObject\ArrayObject|User
	 */
	private function _setupAuthLayer() {
		return $this->str($this->request()->getCurrentUrl(true)->getPath())->match($this->getUrlPattern());
	}

	/**
	 * Method that validates the submitted credentials with defined firewall user providers.
	 * If authentication is valid, a user object is created and a token is storred.
	 *
	 * @param Login $login
	 *
	 * @return bool|UserAbstract
	 * @throws FirewallException
	 */
	private function _authenticate(Login $login) {
		$user = $this->_getUserFromUserProvider($login->getUsername());
		if($user)
		{
			// once we have the user, let's validate the credentials
			if($this->_encoder->verifyPasswordHash($login->getPassword(), $user->getPassword())) {
				// if credentials are valid, let's create the token
				$this->getToken()->saveUser($user);

				return $user;
			} else {
				return false;
			}
		}

		return false;
	}

	/**
	 * Tries to load user object from the registered user providers by its username.
	 *
	 * @param string $username Username of the user that you wish to load.
	 *
	 * @return UserAbstract|bool Instance of UserAbstract, if user is found, or false if user is not found.
	 * @throws FirewallException
	 */
	private function _getUserFromUserProvider($username) {
		foreach ($this->_userProviders as $provider) {
			try {
				$user = $provider->getUserByUsername($username);
				if($user)
				{
					$user->setIsAuthenticated(true);
					return $user;
				}
			}catch (UserNotFoundException $e) {
				return false;
			} catch (\Exception $e) {
				throw new FirewallException($e->getMessage());
			}
		}
	}

	/**
	 * Initializes the Token.
	 */
	private function _initToken() {
		$this->_token = new Token($this->_getTokenName(), $this->getConfig()->remember_me);
	}

	/**
	 * Get the current token.
	 *
	 * @return Token
	 */
	function getToken() {
		return $this->_token;
	}

	/**
	 * Returns the token name.
	 *
	 * @return string
	 */
	private function _getTokenName() {
		return 'wf_token_' . $this->_firewallKey . '_realm';
	}

	/**
	 * Checks if current request matches the login page url.
	 *
	 * @return bool True if we are on the login page.
	 *
	 * @throws FirewallException
	 */
	private function _isLoginPage() {
		$currentUrl = $this->request()->getCurrentUrl();
		if(!isset($this->getConfig()->login->path)) {
			throw new FirewallException('Invalid firewall configuration. Missing configuration param: "login.path".');
		}
		$loginUrl = $this->request()->getCurrentUrl(true)->setPath($this->getConfig()->login->path)->__toString();

		return ($currentUrl == $loginUrl);
	}

	/**
	 * Checks if current request matches the login submit page urls.
	 *
	 * @return bool
	 *
	 * @throws FirewallException
	 */
	private function _isLoginSubmitPage() {
		$currentUrl = $this->request()->getCurrentUrl();
		if(!isset($this->getConfig()->login->submit_path)) {
			throw new FirewallException('Invalid firewall configuration. Missing configuration param: "login.submit_path".');
		}
		$loginSubmitUrl = $this->request()->getCurrentUrl(true)->setPath($this->getConfig()->login->submit_path)
						  ->__toString();

		return ($currentUrl == $loginSubmitUrl);
	}

	/**
	 * Checks if current request matches the logout page url.
	 *
	 * @return bool True if we are on the logout page.
	 *
	 * @throws FirewallException
	 */
	private function _isLogoutPage() {
		$currentUrl = $this->request()->getCurrentUrl();
		if(!isset($this->getConfig()->logout->path)) {
			throw new FirewallException('Invalid firewall configuration. Missing configuration param: "logout.path".');
		}
		$logoutUrl = $this->request()->getCurrentUrl(true)->setPath($this->getConfig()->logout->path)->__toString();

		return ($currentUrl == $logoutUrl);
	}

	/**
	 * Get the authentication provider.
	 *
	 * @return AuthenticationInterface
	 *
	 * @throws FirewallException
	 */
	private function _getAuthProvider() {
		if(is_null($this->_authProvider)) {
			try {
				$this->_authProvider = $this->factory($this->getConfig()->login->provider,
													  '\Webiny\Component\Security\Authentication\Providers\AuthenticationInterface');
			} catch (Exception $e) {
				throw new FirewallException($e->getMessage());
			}
		}

		return $this->_authProvider;
	}
}