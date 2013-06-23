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
 * Argument class serves as a wrapper for service arguments.<br />
 * It contains the value from config file and knows how to get the real value of it, like including other services, instantiating classes, etc.
 *
 * @package         Webiny\Component\ServiceManager
 */
class Argument
{
	use StdLibTrait;

	/**
	 * Simple value, class name or service name
	 */
	private $_value;

	/**
	 * Create Argument instance
	 * @param mixed $argument Value to wrap into Argument instance
	 */
	public function __construct($argument) {
		$this->_value = $argument;
	}

	/**
	 * Get real Argument value
	 * @return mixed
	 */
	public function value() {
		/**
		 * If 'object' key exists - it's either a class or service
		 **/
		if($this->isArray($this->_value) && $this->arr($this->_value)->keyExists('object')) {
			$this->_value = $this->arr($this->_value);
			$this->_value = $this->_createValue(
				$this->_value->key('object'),
				$this->_value->key('object_arguments', [], true)
			);
		} else {
			$this->_value = $this->_createValue($this->_value);
		}

		return $this->_value;
	}

	/**
	 * Create proper argument value
	 *
	 * @param mixed $object
	 * @param array $arguments
	 *
	 * @throws ServiceManagerException
	 *
	 * @return mixed|object
	 */
	private function _createValue($object, $arguments = []) {

		if(!$this->isArray($arguments)) {
			throw new ServiceManagerException(ServiceManagerException::INVALID_SERVICE_ARGUMENTS_TYPE, [$object]);
		}

		if(!$this->isString($object)){
			return $object;
		}

		$object = $this->str($object);

		if($object->startsWith('@')) {
			$arguments = $this->arr($arguments)->count() > 0 ? $arguments : null;
			return ServiceManager::getInstance()->getService($object->val(), $arguments);
		} else {
			$value = $object->val();
			if(class_exists($value)) {
				$reflection = new \ReflectionClass($value);

				return $reflection->newInstanceArgs($arguments);
			}

			return $value;
		}
	}
}