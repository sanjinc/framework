<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace WF\StdLib\StdObject;

use WF\StdLib\Config\ConfigAbstract;
use WF\StdLib\StdObject\ArrayObject\ArrayObject;
use \WF\StdLib\StdObject\StdObjectException;
use WF\StdLib\StdObject\StringObject\StringObject;
use WF\StdLib\ValidatorTrait;

/**
 * Standard object abstract class.
 * Extend this class when you want to create your own standard object.
 *
 * @package         WF\StdLib\StdObject
 */

abstract class StdObjectAbstract implements StdObjectInterface
{
	use ValidatorTrait;

	/**
	 * @var ConfigAbstract
	 */
	private $_config = null;
	/**
	 * ArrayObject that caches the names of standard objects. This is used by StdObjectAbstract::_getStdObjectName
	 * @var ArrayObject
	 */
	private static $_stdObjectName = null;

	/**
	 * Return, or update, current standard objects value.
	 *
	 * @param null $value If $value is set, value is updated and ArrayObject is returned.
	 *
	 * @return array|ArrayObject
	 */
	public function val($value = null) {
		if(!$this->isNull($value)){
			$this->_value = $value;

			return $this;
		}

		return $this->_value;
	}

	/**
	 * Returns an instance to current object.
	 * @return $this
	 */
	protected function _getObject(){
		return $this;
	}

	/**
	 * Throw a standard object exception.
	 *
	 * @param $message
	 *
	 * @return StdObjectException
	 */
	public function exception($message) {
		return new StdObjectException($message);
	}

	/**
	 * Get the default config for the given standard object.
	 * IMPORTANT: This function returns an object that changes the default settings of this standard object.
	 *
	 * @return mixed
	 * @throws \Exception|StdObjectException
	 */
	static protected function _getDefaultConfig() {
		try {
			$configClassName = self::_getStdObjectConfigClassName();
		} catch (StdObjectException $e) {
			throw $e;
		}

		return new $configClassName(true);
	}

	/**
	 * Returns config object for the given standard object.
	 * IMPORTANT: This function controlls the config for current object instance.
	 * If you want to control the default config, use self::_getDefaultConfig
	 *
	 * @throws \Exception|StdObjectException
	 * @return mixed
	 */
	protected function _getConfig() {
		try {
			$configClassName = $this->_getStdObjectConfigClassName();
		} catch (StdObjectException $e) {
			throw $e;
		}


		if(self::isNull($this->_config)) {
			$this->_config = new $configClassName(false);
		}

		return $this->_config;
	}

	/**
	 * Returns the name of standard object config class, or throws a StdObjectException if config class does not exist.
	 *
	 * @return string
	 * @throws StdObjectException
	 */
	static private function _getStdObjectConfigClassName() {
		$stdObjectName = self::_getStdObjectName();
		$configClassName = 'WF\StdLib\StdObject\\' . $stdObjectName . '\\' . $stdObjectName . 'Config';
		if(!self::classExists($configClassName)) {
			throw new StdObjectException('StdObjectAbstract: Config class for "' . $stdObjectName . '" standard object does not exist.');
		}

		return $configClassName;
	}

	/**
	 * Returns the name of current standard object without its namespace.
	 *
	 * @return ArrayObject|StringObject
	 */
	static private function _getStdObjectName() {
		// check if self::$_stdObjectName is created
		if(self::isNull(self::$_stdObjectName)) {
			self::$_stdObjectName = new ArrayObject([]);
		}

		// get called class name (with full namespace)
		$cc = get_called_class();

		// check if we already have an entry for this class name
		if(!self::$_stdObjectName->keyExists($cc)) {
			$str = new StringObject($cc);
			$className = $str->explode('\\')->last();
			self::$_stdObjectName->key($cc, $className);
		} else {
			$className = self::$_stdObjectName->key($cc);
		}

		return $className;
	}
}