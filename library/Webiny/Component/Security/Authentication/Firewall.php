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
use Webiny\Component\EventManager\EventManagerTrait;
use Webiny\Component\Http\HttpTrait;
use Webiny\Component\Security\Authentication\Providers\AuthenticationInterface;
use Webiny\Component\Security\Authentication\Providers\Login;
use Webiny\Component\Security\Encoder\Encoder;
use Webiny\Component\Security\SecurityEvent;
use Webiny\Component\Security\User\AnonymousUser;
use Webiny\Component\Security\User\Exceptions\UserNotFoundException;
use Webiny\Component\Security\User\Providers\Memory;
use Webiny\Component\Security\Token\Token;
use Webiny\Component\Security\User\UserAbstract;
use Webiny\Component\StdLib\Exception\Exception;
use Webiny\Component\StdLib\FactoryLoaderTrait;
use Webiny\Component\StdLib\SingletonTrait;
use Webiny\Component\StdLib\StdLibTrait;

/**
 * This is the main class for authentication layer.
 * The firewall class check if users is authenticated and holds the methods for authentication.
 *
 * @package         Webiny\Component\Security\Authentication
 */

class Firewall
{

	use HttpTrait, StdLibTrait, FactoryLoaderTrait, EventManagerTrait;

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
	 * @var \Webiny\Component\Config\ConfigObject
	 */
	private $_authProviderConfig;

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
	 * Checks if the auth layer should be installed for current request.
	 * If we cannot match the current url using the pattern from the config, auth layer will not be installed.
	 *
	 * @return bool
	 */
	public function isInsideFirewall() {
		return $this->str($this->request()->getCurrentUrl(true)->getPath())->match($this->getUrlPattern());
	}


	/**
	 * This method tries to initialize the firewall.
	 * If firewall doesn't match its url pattern, false is returned, otherwise authentication process is triggered.
	 * If user is authenticated UserAbstract is returned, otherwise the firewall will redirect the user to the login page.
	 *
	 * @return bool|UserAbstract
	 */
	function init() {
		// init token
		$this->_initToken();

		// get user
		$this->getUser();

		if($this->_isLogoutPage()) {
			$this->processLogout();
		}

		// get user
		return $this->_user;
	}

	/**
	 * This method is triggered on the request that requires an authenticated user, but the current user in not
	 * authenticated.
	 *
	 * @throws FirewallException
	 * @return bool|UserAbstract Upon valid authentication an instance of UserAbstract is returned, otherwise false is returned.
	 */
	public function setupAuth() {

		if($this->_isLoginSubmitPage()) {
			try {
				// if we are on login page, first try to get the instance of Login object from current auth provider
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
				$this->eventManager()->fire(SecurityEvent::LOGIN_INVALID, new SecurityEvent(new AnonymousUser()));

				// redirect to failure_path
				$this->request()->redirect($this->request()->getCurrentUrl(true)
										   ->setPath($this->getConfig()->login->failure_path));
			} else {
				$this->_getAuthProvider()->loginSuccessfulCallback($user);
				$this->eventManager()->fire(SecurityEvent::LOGIN_VALID, new SecurityEvent($user));

				// redirect to target
				$url = $this->url($this->request()->getCurrentUrl(true)->getDomain())
					   ->setPath($this->getConfig()->login->target_path);
				$this->request()->redirect($url);
			}
		} else {
			if(!$this->_isLoginPage()) {
				// redirect to login path
				// !!! Hacked because of .htaccess parameter "r" was passed in redirect,
				// !!! which caused an error in Weby app
				$this->request()->redirect($this->request()->getCurrentUrl(true)
										   ->setPath($this->getConfig()->login->login_path)->setQuery(''));
			}
		}

		throw new FirewallException('Error processing authentication.');
	}

	/**
	 * This method deletes user auth token and calls the logoutCallback on current login provider.
	 * After that, it replaces the current user instance with an instance of AnonymousUser and redirects the request to
	 * the logout.target.
	 */
	function processLogout() {
		$this->getToken()->deleteUserToken();
		if($this->_user->isAuthenticated()) {
			$this->_getAuthProvider()->logoutCallback();
		}
		$this->_user = new AnonymousUser();

		// Another hack, the same case like in setupAuth() method
		$this->request()->redirect($this->request()->getCurrentUrl(true)->setPath($this->getConfig()->logout->target)->setQuery(''));
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

			if(!$tokenData) {
				return $this->_user;
			} else {
				$this->_user->populate($tokenData->getUsername(), '', $tokenData->getRoles(), true);
				$this->_user->setAuthProviderDriver($tokenData->getAuthProviderDriver());

				return $this->_user;
			}
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
	 * Returns the config of current auth provider based on current url.
	 * If current url doesn't match any login auth provider, and exception will be thrown.
	 *
	 * @throws FirewallException
	 * @return ConfigObject
	 */
	private function _getAuthProviderConfig() {

		// have we already fetched the auth config
		if($this->_authProviderConfig) {
			return $this->_authProviderConfig;
		}

		// we match the auth provider based on the current url
		// every auth provider must have a different 'submit_path'
		// we can get the auth config only if current request url is on the submit path
		$currentPath = $this->request()->getCurrentUrl(true)->getPath();
		$currentAuthDriver = $this->_user->getAuthProviderDriver();

		$providers = $this->getConfig()->get('login.providers', []);
		foreach ($providers as $pKey => $pData) {
			$submitPath = $pData->get('submit_path', '');
			$driver = $pData->get('driver', 'none');
			if($submitPath == '') {
				throw new FirewallException('Submit path for auth provider "' . $pKey . '" is not defined.');
			}

			if($submitPath == $currentPath) {
				$this->_authProviderConfig = $pData;
			}

			if($driver == $currentAuthDriver) {
				$this->_authProviderConfig = $pData;
				break; // break is only added on this check because the priority has the current auth provider
			}
		}

		if(!$this->_authProviderConfig) {
			throw new FirewallException('Unable to detect the current authentication provider.');
		}

		return $this->_authProviderConfig;
	}

	/**
	 * Method that validates the submitted credentials with defined firewall user providers.
	 * If authentication is valid, a user object is created and a token is stored.
	 * This method just calls the 'authenticate' method on current user object, and if auth method returns true,
	 * we create a token and return the user instance.
	 *
	 * @param Login $login
	 *
	 * @return bool|UserAbstract
	 * @throws FirewallException
	 */
	private function _authenticate(Login $login) {
		try {
			$user = $this->_getUserFromUserProvider($login);
		} catch (\Exception $e) {
			return false;
		}

		if($user) {
			if($user->authenticate($login, $this->_encoder)) {
				// save info about current auth provider into user instance
				$user->setAuthProviderDriver($this->_getAuthProviderConfig()->get('driver', ''));

				// save token
				$this->getToken()->saveUser($user);

				return $user;
			} else {
				return false;
			}
		}

		return false;
	}

	/**
	 * Tries to load user object from the registered user providers based on the data inside the Login object instance.
	 *
	 * @param Login $login Login object received from authentication provider.
	 *
	 * @return UserAbstract|bool Instance of UserAbstract, if user is found, or false if user is not found.
	 * @throws FirewallException
	 */
	private function _getUserFromUserProvider(Login $login) {
		foreach ($this->_userProviders as $provider) {
			try {
				$user = $provider->getUser($login);
				if($user) {
					return $user;
				}
			} catch (UserNotFoundException $e) {
				// next user provider
			} catch (\Exception $e) {
				throw new FirewallException($e->getMessage());
			}
		}

		return false;
	}

	/**
	 * Initializes the Token.
	 */
	private function _initToken() {
		$this->_token = new Token($this->_getTokenName(),
								  $this->getConfig()->get('remember_me', false),
								  $this->getConfig()->get('security_key', ''));
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
		if(!isset($this->getConfig()->login->login_path)) {
			throw new FirewallException('Invalid firewall configuration. Missing configuration param: "login.login_path".');
		}

		return $this->_isOnPath($this->getConfig()->login->login_path);
	}

	/**
	 * Checks if current request matches the login submit page urls.
	 *
	 * @return bool
	 *
	 * @throws FirewallException
	 */
	private function _isLoginSubmitPage() {
		try {
			return $this->_isOnPath($this->_getAuthProviderConfig()->submit_path);
		} catch (\Exception $e) {
			// if we cannot load the auth provider, we are not on the login submit page
			return false;
		}
	}

	/**
	 * Checks if current request matches the logout page url.
	 *
	 * @return bool True if we are on the logout page.
	 *
	 * @throws FirewallException
	 */
	private function _isLogoutPage() {
		if(!isset($this->getConfig()->logout->path)) {
			throw new FirewallException('Invalid firewall configuration. Missing configuration param: "logout.path".');
		}

		return $this->_isOnPath($this->getConfig()->logout->path);
	}

	/**
	 * Get the authentication provider.
	 * You must be on the submit_path if you want to get the auth provider.
	 *
	 * @return AuthenticationInterface
	 *
	 * @throws FirewallException
	 */
	private function _getAuthProvider() {
		if(is_null($this->_authProvider)) {
			// auth provider config
			$authProviderConfig = $this->_getAuthProviderConfig();

			// optional params that will be passed to auth provider constructor
			$params = [];
			if($authProviderConfig->get('params', false)) {
				$params = $authProviderConfig->get('params')->toArray();
			}

			try {
				$this->_authProvider = $this->factory($authProviderConfig->driver,
													  '\Webiny\Component\Security\Authentication\Providers\AuthenticationInterface',
													  $params
				);
			} catch (Exception $e) {
				throw new FirewallException($e->getMessage());
			}
		}

		return $this->_authProvider;
	}

	/**
	 * Method that checks if current request is on the given $path.
	 *
	 * @param string $path Path to match.
	 *
	 * @return bool
	 */
	private function _isOnPath($path) {
		$currentUrl = $this->str($this->request()->getCurrentUrl(true)->getPath())->caseLower();
		$path = $this->str($path)->caseLower();

		return $currentUrl->equals($path->val());
	}
}