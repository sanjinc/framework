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

/**
 * ServiceCreator class is responsible for taking a ServiceConfig and building a service instance.
 *
 * @package         Webiny\Component\ServiceManager
 */

class ServiceCreator
{
	use StdLibTrait;

	private $_config;

	/**
	 * @param ServiceConfig $config Compiled service config
	 */
	public function __construct(ServiceConfig $config) {
		$this->_config = $config;
	}

	/**
	 * Get service instance
	 * @return object
	 */
	public function getService() {
		// Get real arguments values
		$arguments = [];
		foreach ($this->_config->getArguments() as $arg) {
			$arguments[] = $arg->value();
		}

		$service = $this->_getServiceInstance($arguments);

		// Call methods
		foreach ($this->_config->getCalls() as $call) {
			$arguments = [];
			foreach ($call[1] as $arg) {
				$arguments[] = $arg->value();
			}
			call_user_func_array([
								 $service,
								 $call[0]
								 ], $arguments);
		}

		return $service;
	}

	private function _getServiceInstance($arguments) {
		// Create service instance
		if($this->isNull($this->_config->getFactory())) {
			$reflection = new \ReflectionClass($this->_config->getClass());

			return $reflection->newInstanceArgs($arguments);
		}

		// Build factory instance
		$service = $this->_config->getFactory()->value();
		$arguments = [];
		foreach ($this->_config->getMethodArguments() as $arg) {
			$arguments[] = $arg->value();
		}

		return call_user_func_array([
									$service,
									$this->_config->getMethod()
									], $arguments);
	}
}