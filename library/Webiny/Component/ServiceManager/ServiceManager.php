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
use Webiny\Component\StdLib\SingletonTrait;
use Webiny\Component\StdLib\StdLibTrait;
use Webiny\Component\StdLib\StdObject\ArrayObject\ArrayObject;
use Webiny\WebinyTrait;

/**
 * ServiceManager is the main class for working with services.
 * @package         Webiny\Component\ServiceManager
 */

class ServiceManager
{
	use StdLibTrait, SingletonTrait, WebinyTrait;

	private $_compiledConfig;
	private $_services;
	private $_taggedServices = null;
	private static $_references;

	/**
	 * Get service instance by given name nad optional arguments
	 *
	 * @param string     $serviceName Requested service name
	 * @param null|array $arguments   (Optional) Arguments for service constructor
	 *
	 * @return object
	 */
	public function getService($serviceName, $arguments = null) {
		return $this->_getService($serviceName, $arguments);
	}

	/**
	 * Get multiple services by tag
	 *
	 * @param string      $tag       Tag to use for services filter
	 * @param null|string $forceType (Optional) Return only services which are instances of $forceType
	 *
	 * @return array
	 */
	public function getServicesByTag($tag, $forceType = null) {
		if($this->isNull($this->_taggedServices)) {
			$this->_tagifyServices($this->webiny()->getConfig()->services->toArray());
		}

		$services = [];
		foreach ($this->_taggedServices->key($tag, [], true) as $serviceName) {
			$service = $this->getService($serviceName);
			if(!$this->isNull($forceType) && !$this->isInstanceOf($service, $forceType)) {
				continue;
			}
			$services[$serviceName] = $service;
		}

		return $services;
	}

	protected function init() {
		$this->_services = $this->arr();
		$this->_compiledConfig = $this->arr();
		self::$_references = $this->arr();
	}

	private function _getService($serviceName, $arguments = null) {
		$serviceName = $this->str($serviceName)->trimLeft("@")->val();

		// Check circular referencing
		if(self::$_references->keyExists($serviceName)) {
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

		/**
		 * @var $config ServiceConfig
		 */
		$config = $this->_compiledConfig->key($serviceName);

		// Check if arguments for overriding exist
		if(!$this->isNull($arguments) && $this->isArray($arguments)) {
			$compiler = new ConfigCompiler($serviceName);
			$compiler->replaceArguments($config, $arguments);
		}

		// Construct service container and get service instance
		$serviceCreator = new ServiceCreator($config, $arguments);
		$service = $serviceCreator->getService();

		// Unset service name reference
		self::$_references->removeKey($serviceName);

		// Store instance if this service has a CONTAINER scope
		if($config->getScope() == ServiceScope::CONTAINER) {
			$this->_services->key($serviceName, $service);
		}

		return $service;
	}

	/**
	 * Traverse services config array and group services by tags
	 *
	 * @param array|ArrayObject $config Config array
	 * @param string            $prefix Previous level of nesting (needed to construct full service name)
	 */
	private function _tagifyServices($config, $prefix = '') {
		$this->_taggedServices = $this->arr();
		foreach ($config as $serviceName => $sConfig) {
			$level = $this->str($prefix . '.' . $serviceName)->trimLeft('.')->val();
			$sConfig = $this->arr($sConfig);
			if($sConfig->keyExists('tags')) {
				// Abstract service can not contain 'tags' key
				if($sConfig->keyExists('abstract') && $sConfig->key('abstract') === true) {
					/**
					 * @TODO: if you need to use 'tags' in abstract services you will add extra pass on the entire
					 * services config array, to find child services
					 *
					 * You will also need to handle 'tags' key in parent/child config merging to be able to add/override
					 * 'tags' in your child service
					 */
					continue;
				}
				// Concrete services
				foreach ($sConfig->key('tags') as $tag) {
					$this->_taggedServices->key($tag, $this->arr(), true)->append($level);
				}
			} elseif(!$sConfig->keyExists('class') && !$sConfig->keyExists('factory') && !$sConfig->keyExists('parent')) {
				$this->_tagifyServices($sConfig, $level);
			}
		}
	}
}