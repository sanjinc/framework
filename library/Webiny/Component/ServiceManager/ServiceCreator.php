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

class ServiceCreator
{
	use StdLibTrait;

	private $_config;

	public function __construct(ServiceConfig $config) {
		$this->_config = $config;
	}

	public function getService() {
		// Get real arguments values
		$arguments = [];
		foreach ($this->_config->getArguments() as $arg) {
			$arguments[] = $arg->value();
		}

		// Create service instance
		$reflection = new \ReflectionClass($this->_config->getClass());
		$service = $reflection->newInstanceArgs($arguments);

		// Call methods
		foreach ($this->_config->getCalls() as $call) {
			$arguments = [];
			foreach ($call[1] as $arg) {
				$arguments[] = $arg->value();
			}
			call_user_func_array([$service, $call[0]], $arguments);
		}

		return $service;
	}

	public function getScope() {
		return $this->_config->getScope();
	}
}