<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Security;

use Webiny\Component\Config\ConfigObject;
use Webiny\Component\Security\Authentication\Firewall;
use Webiny\Component\Security\Authorization\AccessControl;
use Webiny\Component\Security\Encoder\Encoder;
use Webiny\Component\Security\Role\RoleHierarchy;
use Webiny\Component\Security\User\Providers\Memory\MemoryProvider;
use Webiny\Component\ServiceManager\ServiceManager;
use Webiny\Component\ServiceManager\ServiceManagerException;
use Webiny\StdLib\Exception\Exception;
use Webiny\StdLib\FactoryLoaderTrait;
use Webiny\StdLib\SingletonTrait;
use Webiny\StdLib\StdLibTrait;
use Webiny\WebinyTrait;

/**
 * Description
 *
 * @package         Webiny\Component\Security
 */

class Security
{
	use SingletonTrait, WebinyTrait, StdLibTrait, FactoryLoaderTrait;

	/**
	 * @var ConfigObject
	 */
	private $_config;
	/**
	 * @var Firewall
	 */
	private $_firewall;
	/**
	 * @var array
	 */
	private $_encoders = [];
	/**
	 * @var array
	 */
	private $_userProviders = [];

	/**
	 * Initializes the security layer.
	 *
	 * @throws \Exception|\Webiny\Component\ServiceManager\ServiceManagerException
	 * @return bool
	 */
	public function init() {
		// validate the config
		$this->_config = $this->webiny()->getConfig()->get('security', false);
		if(!$this->_config) {
			return false;
		}

		// initialize user providers..there has to be at least one user provider
		try {
			$this->_initUserProviders();
		} catch (\Exception $e) {
			throw new SecurityException($e);
		}

		// initialize the encoder
		try {
			$this->_initEncoders();
		} catch (\Exception $e) {
			throw new SecurityException($e);
		}

		// setup authentication layer - firewalls -> we only keep the firewall that accepts the current request
		$firewalls = $this->_getConfig()->get('firewalls', []);
		foreach ($firewalls as $firewallKey => $firewallConfig) {
			$this->_firewall = new Firewall($firewallKey,
											$firewallConfig,
											$this->_getFirewallProviders($firewallKey),
											$this->_getFirewallEncoder($firewallKey));

			if($this->_firewall->isInsideFirewall()){
				break;
			}
		}

		// lets validate the user
		$user = $this->_firewall->init();
		if((!$user || !$user->isAuthenticated()) && !$this->_firewall->getAnonymousAccess()){
			//launch the auth process because user is not authenticated
			try{
				$user = $this->_firewall->setupAuth();
			}catch (\Exception $e){
				throw $e;
			}

			if(!$user){
				throw new Exception('Unable to authenticate the user.');
			}
		}

		// read role hierarchy
		$roleHierarchy = new RoleHierarchy($this->_getConfig()->role_hierarchy->toArray());

		// update users roles based on the role hierarchy
		$user->setRoles($roleHierarchy->getAccessibleRoles($user->getRoles()));

		// process access control
		$accessControl = new AccessControl($user, $this->_getConfig()->get('access_control', false));
		if(!$accessControl->isUserAllowedAccess()){
			try{
				$user = $this->_firewall->setupAuth();
			}catch (\Exception $e){
				throw $e;
			}

			if(!$user){
				throw new Exception('Unable to authenticate the user.');
			}
		}
		echo 'security: account valid';
		print_r($user);
	}

	/**
	 * Initialize user providers defined for this firewall.
	 *
	 * @throws SecurityException
	 */
	private function _initUserProviders() {
		$providers = $this->_getConfig()->get('providers', []);
		if(count($providers) < 1) {
			throw new SecurityException('There are no user providers defined. Please define at last one provider.');
		}

		foreach ($providers as $pk => $provider) {
			if(is_object($provider)) {
				if(isset($provider->driver)) {
					try {
						$params = $provider->get('params', []);
						$this->_userProviders[$pk] = $this->factory($provider->driver,
																	'\Webiny\Component\Security\User\UserProviderInterface',
																	$params);
					} catch (\Exception $e) {
						throw new SecurityException($e->getMessage());
					}
				} else {
					$this->_userProviders[$pk] = new MemoryProvider($provider->toArray());
				}
			} else {
				throw new SecurityException('Unable to read user provider "' . $pk . '".');
			}
		}
	}

	/**
	 * Create the encoder instance.
	 * If encoder is not defined, we create an instance of Null encoder.
	 */
	private function _initEncoders() {
		$encoders = $this->_getConfig()->get('encoders', []);
		if(count($encoders) > 0) {
			foreach ($encoders as $ek => $encoder) {
				// encoder params
				$driver = $encoder->get('driver', false);
				if(!$driver) {
					throw new SecurityException('Encoder "driver" param must be present.');
				}
				$salt = $encoder->get('salt', '');
				$params = $encoder->get('params', false);
				if($params){
					$params = $params->toArray();
				}

				// encoder instance
				$this->_encoders[$ek] = new Encoder($driver, $salt, $params);
			}
		}

		if(!isset($this->_encoders['_null'])) {
			$encoder = '\Webiny\Component\Security\Encoder\Drivers\Null';
			$salt = '';
			$params = null;
			$this->_encoders['_null'] = new Encoder($encoder, $salt, $params);
		}
	}

	/**
	 * Returns an array of instances of user providers for the given firewall.
	 * NOTE: this function also checks for chain providers.
	 *
	 * @param string $firewallKey Firewall name.
	 *
	 * @return array Array of user provider instances for the given firewall.
	 * @throws SecurityException
	 */
	private function _getFirewallProviders($firewallKey) {
		$userProviders = [];

		// get the provider name
		$provider = $this->_getFirewallConfig($firewallKey)->get('provider', false);
		if(!$provider) {
			throw new SecurityException('Firewall user provider is not defined.');
		}

		// check if it's a chain provider
		$chainProviders = $this->_getConfig()->get('chain_providers', false);
		if($chainProviders) {
			$chainProvider = $chainProviders->get($provider, false);
			if(!$chainProvider) {
				throw new SecurityException('Chain provider "' . $provider . '" not found.');
			}
			$userProviders = $chainProvider->toArray();
		} else {
			$userProviders[] = $provider;
		}

		if(count($userProviders) < 1) {
			throw new SecurityException('Unable to detect the user provider definition for "' . $firewallKey . '" firewall.');
		}

		// once we have our list of providers, lets connect them to their instances
		$providerInstances = [];
		foreach ($userProviders as $up) {
			if(!isset($this->_userProviders[$up])) {
				throw new SecurityException('User provider "' . $up . '" is missing its configuration.');
			}
			$providerInstances[$up] = $this->_userProviders[$up];
		}

		return $providerInstances;
	}

	/**
	 * Returns the encoder instance for the given firewall.
	 *
	 * @param string $firewallKey Firewall name.
	 *
	 * @return Encoder
	 * @throws SecurityException
	 */
	private function _getFirewallEncoder($firewallKey) {
		$encoder = $this->_getFirewallConfig($firewallKey)->get('encoder', '_null');
		if(!isset($this->_encoders[$encoder])) {
			throw new SecurityException('Encoder "' . $encoder . '" is not defined in your security.encoders config.');
		}

		return $this->_encoders[$encoder];
	}

	/**
	 * Returns the security config object.
	 *
	 * @return ConfigObject
	 */
	private function _getConfig() {
		return $this->_config;
	}

	/**
	 * @param $firewallKey
	 *
	 * @return ConfigObject
	 */
	private function _getFirewallConfig($firewallKey) {
		return $this->_getConfig()->firewalls->{$firewallKey};
	}
}
