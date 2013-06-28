<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\ServiceManager;

use Webiny\StdLib\StdLibTrait;
use Webiny\WebinyTrait;


/**
 * ConfigCompiler class is responsible for compiling a valid ServiceConfig object.<br />
 * It parses the config file, manages inheritance, wraps arguments into Argument objects
 * and makes sure the config is valid.
 *
 * @package         Webiny\Component\ServiceManager
 */

class ConfigCompiler
{
	use StdLibTrait, WebinyTrait;

	private $_config;
	private $_parameters;
	private $_serviceName;
	private $_serviceConfig;


	public function __construct($serviceName) {
		$this->_serviceName = $serviceName;
		$this->_config = $this->webiny()->getConfig()->services->toArray();
		$this->_parameters = $this->webiny()->getConfig()->parameters->toArray();
	}

	public function compile() {
		/**
		 * Need to assign to $this->_serviceConfig each time, because these methods
		 * can be called recursively from different context
		 **/
		$this->_serviceConfig = $this->_getServiceConfig($this->_serviceName);
		$this->_manageInheritance();
		$this->_serviceConfig = $this->_insertParameters($this->_serviceConfig);
		$this->_buildArguments('arguments');
		$this->_buildArguments('method_arguments');
		$this->_buildCallsArguments();
		$this->_buildFactoryArgument();

		return $this->_buildServiceConfig();
	}

	public function replaceArguments($config, $arguments) {
		$newArguments = [];
		foreach ($arguments as $arg) {
			$newArguments[] = new Argument($arg);
		}
		$config->setArguments($newArguments);
	}

	private function _manageInheritance() {
		$config = $this->_serviceConfig;
		if($config->keyExists('parent')) {
			$parentServiceName = $this->str($config->key('parent'))->trimLeft("@")->val();
			$parentConfig = $this->_getServiceConfig($parentServiceName);
			if(!$parentConfig->keyExists('abstract')) {
				throw new ServiceManagerException(
					ServiceManagerException::SERVICE_IS_NOT_ABSTRACT,
					[$config->key('parent')]
				);
			}
			$config = $this->_extendConfig($config, $parentConfig);
		}

		// Check if it's a potentially valid service definition
		if(!$config->keyExists('class') && !$config->keyExists('factory')) {
			throw new ServiceManagerException(ServiceManagerException::SERVICE_CLASS_KEY_NOT_FOUND, [$this->_serviceName]);
		}

		$this->_serviceConfig = $config;
	}

	private function _extendConfig($config, $parentConfig) {

		$configCalls = null;
		$overrideCalls = false;

		// Get calls arrays
		if($config->keyExists('calls')) {
			$configCalls = $config->key('calls');
		} elseif($config->keyExists('!calls')) {
			$configCalls = $config->key('!calls');
			$overrideCalls = true;
		}
		$parentCalls = $parentConfig->key('calls');

		// Merge basic values
		$config = $parentConfig->merge($config);

		// Remove unnecessary keys
		$config->removeKey('parent')->removeKey('abstract')->removeKey('calls');

		// Merge calls
		if(!$this->isNull($configCalls) && !$this->isNull($parentCalls)) {
			if($overrideCalls) {
				$config->key('!calls', $configCalls);

				return;
			}

			foreach ($configCalls as $call) {
				$call = $this->arr($call);
				if($call->keyExists(2)) {
					$parentCalls[$call[2]] = $call->val();
				} else {
					$parentCalls[] = $call->val();
				}
			}
			$config->key('calls', $parentCalls);
		} elseif($this->isNull($configCalls) && !$this->isNull($parentCalls)) {
			$config->key('calls', $parentCalls);
		} elseif(!$this->isNull($configCalls) && $this->isNull($parentCalls)) {
			$config->key('calls', $configCalls);
		}

		return $config;
	}

	private function _insertParameters($config) {
		foreach ($config as $k => $v) {
			if($this->isArray($v)) {
				$config[$k] = $this->_insertParameters($v);
			} elseif($this->isString($v)) {
				$str = $this->str($v)->trim();
				if($str->startsWith("%") && $str->endsWith("%")) {
					$parameter = $str->trim("%")->val();
					$config[$k] = $this->_parameters[$parameter];
				}
			}
		}

		return $config;
	}

	private function _getServiceConfig($serviceName) {
		// Load service config
		$namespaces = $this->str($serviceName)->explode('.');
		$config = $this->_config;

		foreach ($namespaces as $namespace) {
			if(empty($config[$namespace])){
				throw new ServiceManagerException(ServiceManagerException::SERVICE_DEFINITION_NOT_FOUND, [$this->_serviceName]);
			}

			$config = $config[$namespace];
		}

/*		if($this->isNull($config)) {
			throw new ServiceManagerException(ServiceManagerException::SERVICE_DEFINITION_NOT_FOUND, [$this->_serviceName]);
		}
*/
		return $this->arr($config);
	}

	private function _buildArguments($key) {
		$newArguments = [];
		if($this->_serviceConfig->keyExists($key)) {
			$arguments = $this->_serviceConfig->key($key);
			if(!$this->isArray($arguments)) {
				throw new ServiceManagerException(ServiceManagerException::INVALID_SERVICE_ARGUMENTS_TYPE, [$this->_serviceName]);
			}
			foreach ($arguments as $arg) {
				$newArguments[] = new Argument($arg);
			}
		}
		$this->_serviceConfig->key($key, $newArguments);
	}

	private function _buildFactoryArgument() {
		if($this->_serviceConfig->keyExists('factory')) {
			$factory = $this->str($this->_serviceConfig->key('factory'));
			$arguments = $this->_serviceConfig->key('arguments', null, true);
			// If it's a STATIC method call - unset all arguments
			if($this->_serviceConfig->key('static', true, true) && !$factory->startsWith('@')) {
				$arguments = [];
			}
			$factoryArgument = new FactoryArgument(
				$this->_serviceConfig->key('factory'),
				$arguments,
				$this->_serviceConfig->key('static')
			);
			$this->_serviceConfig->key('factory', $factoryArgument);
		}
	}

	private function _buildCallsArguments() {
		if($this->_serviceConfig->keyExists('calls')) {
			$calls = $this->_serviceConfig->key('calls');
			foreach ($calls as $callKey => $call) {
				if($this->isArray($call[1])) {
					$newArguments = [];
					foreach ($call[1] as $arg) {
						$newArguments[] = new Argument($arg);
					}
					$calls[$callKey][1] = $newArguments;
				}
			}
			$this->_serviceConfig->key('calls', $calls);
		}
	}

	private function _buildServiceConfig() {
		if($this->_serviceConfig->keyExists('factory') && !$this->_serviceConfig->keyExists('method')) {
			throw new ServiceManagerException(ServiceManagerException::FACTORY_SERVICE_METHOD_KEY_MISSING, [$this->_serviceName]);
		}

		$config = new ServiceConfig();
		$config->setClass($this->_serviceConfig->key('class', null, true));
		$config->setArguments($this->_serviceConfig->key('arguments', [], true));
		$config->setCalls($this->_serviceConfig->key('calls', [], true));
		$config->setScope($this->_serviceConfig->key('scope', ServiceScope::CONTAINER, true));
		$config->setFactory($this->_serviceConfig->key('factory', null, true));
		$config->setMethod($this->_serviceConfig->key('method', null, true));
		$config->setMethodArguments($this->_serviceConfig->key('method_arguments'));
		$config->setStatic($this->_serviceConfig->key('static', true, true));

		return $config;
	}
}