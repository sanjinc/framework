<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Security\Authentication;

use Webiny\Component\Config\ConfigObject;
use Webiny\Component\Http\HttpTrait;
use Webiny\Component\Security\Encoder\Encoder;
use Webiny\Component\Security\User\Providers\Memory;
use Webiny\Component\Security\User\Providers\Memory\MemoryProvider;
use Webiny\Component\Security\Token\Token;
use Webiny\StdLib\FactoryLoaderTrait;
use Webiny\StdLib\SingletonTrait;
use Webiny\StdLib\StdLibTrait;

/**
 * Description
 *
 * @package         Webiny\Component\Security\Authenticatio
 */

class Firewall
{

	use HttpTrait, StdLibTrait, FactoryLoaderTrait;

	private $_config;
	private $_userProviders = [];
	private $_userProviderChain = [];
	private $_firewallKey;
	private $_encoder;
	private $_token;
	private $_user = false;

	function __construct($firewallKey, ConfigObject $firewallConfig) {
		$this->_firewallKey = $firewallKey;
		$this->_config = $firewallConfig;

		// setup authorization layer
		if(!$this->_setupAuthLayer()) {
			return false;
		}

		// init user providers
		$this->_initUserProviders();

		// init encoder
		$this->_initEncoder();

		// init token
		$this->_initToken();

		// get user
		$user = $this->getUser();

		// check if user has access
		// - this is only the auth access check, this means that either we allow anonymous access or don't.
		if(!$this->getAnonymousAccess() && !$user){
			// check if we are maybe on the login page
			if($this->_isLoginPage()){
				die('trigger login');
			}
			// trigger redirect to login page
			$this->request()->redirect($this->request()->getCurrentUrl(true)->setPath($this->getConfig()->login->path));
		}
		die('inside');

		// if true check on authorization layer if user has access

		// if user doesn't have access trigger the authentication process
	}

	function getUser(){
		if(!$this->_user){
			$this->_user = $this->getToken()->getUserFromToken();
		}

		return $this->_user;
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
		return isset($this->_config->anonymous) ? $this->_config->anonymous : false;
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
	 * @return bool|\Webiny\StdLib\StdObject\ArrayObject\ArrayObject
	 */
	private function _setupAuthLayer() {
		return $this->str($this->request()->getCurrentUrl(true)->getPath())->match($this->getUrlPattern());
	}

	/**
	 * Initialize user providers defined for this firewall.
	 *
	 * @throws FirewallException
	 */
	private function _initUserProviders() {
		$providers = $this->getConfig()->providers;
		if(count($providers) < 1) {
			throw new FirewallException('There are no user providers defined. Please define at last one provider.');
		}

		$this->_userProviderChain = isset($this->getConfig()->provider_chain) ? 
									$this->getConfig()->provider_chain->toArray() :
									array_keys($providers->toArray());

		foreach($providers as $pk => $provider){
			if(is_object($provider)){
				if(isset($provider->driver)){
					try{
						$params = isset($provider->params) ? $provider->params : [];
						$this->_userProviders[$pk] = $this->factory($provider,
																	'\Webiny\Component\Security\User\UserProviderInterface',
																	$params);
					}catch (\Exception $e){
						throw new FirewallException($e->getMessage());
					}
				}else{
					$this->_userProviders[$pk] = new MemoryProvider($provider->toArray());
				}
			}else{
				throw new FirewallException('Unable to read user provider "'.$pk.'".');
			}
		}
	}

	/**
	 * Create encoder instance.
	 */
	private function _initEncoder(){
		if(isset($this->getConfig()->encoder)){
			$this->_encoder = new Encoder($this->getConfig()->encoder->driver,
										  $this->getConfig()->encoder->salt,
										  $this->getConfig()->params);
		}
	}

	/**
	 * Get the current encoder.
	 *
	 * @return Encoder
	 */
	private function _getEncoder() {
		return $this->_encoder;
	}

	/**
	 * Initializes the Token.
	 */
	private function _initToken(){
		$this->_token = new Token($this->_getTokenName(), $this->getConfig()->remember_me);
	}

	/**
	 * Get the current token.
	 *
	 * @return Token
	 */
	function getToken(){
		return $this->_token;
	}

	/**
	 * Returns the token name.
	 *
	 * @return string
	 */
	private function _getTokenName(){
		return 'wf_token_'.$this->getRealmName().'_realm';
	}

	/**
	 * Checks if current request matches the login page url.
	 *
	 * @return bool True if we are on the login page.
	 *
	 * @throws FirewallException
	 */
	private function _isLoginPage(){
		$currentUrl = $this->request()->getCurrentUrl();
		if(!isset($this->getConfig()->login->path)){
			throw new FirewallException('Invalid firewall configuration. Missing configuration param: "login.path".');
		}
		$loginUrl = $this->request()->getCurrentUrl(true)->setPath($this->getConfig()->login->path)->__toString();

		return ($currentUrl==$loginUrl);
	}
}