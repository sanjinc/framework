<?php
namespace Webiny\Component\ServiceManager;


use Webiny\StdLib\StdLibTrait;

class FactoryArgument
{
	use StdLibTrait;

	/**
	 * Simple value, class name or service name
	 */
	private $_value;
	private $_arguments;

	/**
	 * @param $resource
	 * @param $arguments If arguments are empty, it's a static call
	 */
	public function __construct($resource, $arguments = null) {
		$this->_value = $resource;
		$this->_arguments = $arguments;
	}

	/**
	 * Get real Argument value
	 * @throws ServiceManagerException
	 *
	 * @return mixed
	 */
	public function value() {

		if(!$this->isString($this->_value)) {
			return $this->_value;
		}

		$arguments = [];
		foreach ($this->_arguments as $arg) {
			$arguments[] = $arg->value();
		}
		$this->_arguments = $arguments;

		$this->_value = $this->str($this->_value);

		if($this->_value->startsWith('@')) {
			$arguments = $this->arr($this->_arguments)->count() > 0 ? $this->_arguments : null;

			return ServiceManager::getInstance()->getService($this->_value->val(), $arguments);
		} else {
			$value = $this->_value->val();
			if(class_exists($value) && !$this->isNull($this->_arguments)) {
				if(!$this->isArray($this->_arguments)) {
					throw new ServiceManagerException(ServiceManagerException::INVALID_SERVICE_ARGUMENTS_TYPE, [$this->_value]);
				}

				$reflection = new \ReflectionClass($value);

				return $reflection->newInstanceArgs($this->_arguments);
			} elseif(class_exists($value) && $this->isNull($this->_arguments)) {
				return $this->_value->val();
			}
			throw new ServiceManagerException(ServiceManagerException::SERVICE_CLASS_DOES_NOT_EXIST, [$this->_value]);
		}
	}
}