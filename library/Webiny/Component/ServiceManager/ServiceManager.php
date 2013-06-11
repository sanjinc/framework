<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */
namespace Webiny\Component\ServiceManager;

use Webiny\Component\Config\Config;
use Webiny\Component\Config\ConfigObject;
use Webiny\StdLib\SingletonTrait;
use Webiny\StdLib\StdLibTrait;
use Webiny\WebinyTrait;

class ServiceManager
{
	use StdLibTrait, SingletonTrait, WebinyTrait;

	private $_compiledConfig;
	private $_services;
	private static $_references;

	public function getService($serviceName, $arguments = null) {
		return $this->_getService($serviceName, $arguments);
	}

	protected function init() {
		$this->_services = $this->arr();
		$this->_compiledConfig = $this->arr();
		self::$_references = $this->arr();
	}

	private function _getService($serviceName, $arguments = null) {
		$serviceName = $this->str($serviceName)->trimLeft("@")->val();

		// Check circular referencing
		if(self::$_references->keyExists($serviceName)){
			throw new ServiceManagerException(ServiceManagerException::SERVICE_CIRCULAR_REFERENCE, [$serviceName]);
		}

		// Check if service instance already exists
		if($this->_services->keyExists($serviceName)) {
			return $this->_services->key($serviceName);
		}

		// Set service name reference for circular referencing checks
		self::$_references->key($serviceName, $serviceName);

		// Check if parsed config already exists
		if(!$this->_compiledConfig->keyExists($serviceName)) {
			$configCompiler = new ConfigCompiler($serviceName);
			$this->_compiledConfig->key($serviceName, $configCompiler->compile());
		}

		// Store compiled config
		$config = $this->_compiledConfig->key($serviceName);

		// Check if arguments for overriding exist
		if(!$this->isNull($arguments) && $this->isArray($arguments)){
			$compiler = new ConfigCompiler($serviceName);
			$compiler->replaceArguments($config, $arguments);
		}
		
		// Construct service container and get service instance
		$serviceCreator = new ServiceCreator($config, $arguments);
		$service = $serviceCreator->getService();

		// Unset service name reference
		self::$_references->removeKey($serviceName);

		// Store instance if this service has a CONTAINER scope
		if($serviceCreator->getScope() == ServiceScope::CONTAINER) {
			$this->_services->key($serviceName, $service);
		}

		return $service;
	}
}