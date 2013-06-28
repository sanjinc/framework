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
use Webiny\Component\Security\Encoder\Encoder;
use Webiny\Component\Security\User\Providers\Memory\MemoryProvider;
use Webiny\Component\ServiceManager\ServiceManager;
use Webiny\Component\ServiceManager\ServiceManagerException;
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
	use SingletonTrait, WebinyTrait, StdLibTrait;

	/**
	 * @var ConfigObject
	 */
	private $_config;
	private $_firewall;
	private $_encoders = [];
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

		// validate additional requirement
		try {
			ServiceManager::getInstance()->getService('crypt.webiny_crypt');
		} catch (ServiceManagerException $e) {
			if($e->getCode() == ServiceManagerException::SERVICE_DEFINITION_NOT_FOUND) {
				throw new SecurityException('Security component requires that you have a service "crypt.webiny_crypt" defined');
			}

			throw $e;
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

		// setup authentication layer - firewalls -> we only keep the firewall that accepts the request
		$firewalls = $this->_getConfig()->get('firewalls', []);
		foreach ($firewalls as $firewallKey => $firewallConfig) {
			$this->_firewall = new Firewall($firewallKey,
											$firewallConfig,
											$this->_getFirewallProviders($firewallKey),
											$this->_getFirewallEncoder($firewallKey));

			$user = $this->_firewall->init();

			if($user) {
				break;
			}
		}

		// read roles
		die(print_r($user));


		// check if user has access based on his role
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
						$this->_userProviders[$pk] = $this->factory($provider,
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
				$params = $encoder->get('params', null)->toArray();

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
