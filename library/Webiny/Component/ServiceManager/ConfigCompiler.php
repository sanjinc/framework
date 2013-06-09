<?php
namespace Webiny\Component\ServiceManager;


use Webiny\StdLib\StdLibTrait;
use Webiny\WebinyTrait;

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
		$this->_buildServiceArguments();
		$this->_buildCallsArguments();

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


		if(!$config->keyExists('class')) {
			throw new ServiceManagerException(ServiceManagerException::SERVICE_CLASS_NOT_FOUND, [$this->_serviceName]);
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
			$config = $config[$namespace];
		}

		if($this->isNull($config)) {
			throw new ServiceManagerException(ServiceManagerException::SERVICE_DEFINITION_NOT_FOUND, [$this->_serviceName]);
		}

		return $this->arr($config);
	}

	private function _buildServiceArguments() {
		if($this->_serviceConfig->keyExists('arguments')) {
			$newArguments = [];
			foreach ($this->_serviceConfig->key('arguments') as $arg) {
				$newArguments[] = new Argument($arg);
			}
			$this->_serviceConfig->key('arguments', $newArguments);
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
		return new ServiceConfig(
			$this->_serviceConfig->key('class'),
			$this->_serviceConfig->key('arguments', [], true),
			$this->_serviceConfig->key('calls', [], true),
			$this->_serviceConfig->key('scope', null, true)
		);
	}
}