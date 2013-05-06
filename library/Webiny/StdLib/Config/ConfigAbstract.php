<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\StdLib\Config;

use Webiny\StdLib\Exception\Exception;
use Webiny\StdLib\StdObject\ArrayObject\ArrayObject;
use Webiny\StdLib\ValidatorTrait;

/**
 * Abstract config class.
 * Use it to pass different config params to objects.
 *
 * @package         Webiny\StdLib\Config
 */
abstract class ConfigAbstract
{

	use ValidatorTrait;

	/**
	 * @var bool
	 */
	private $_overwriteDefault;
	/**
	 * @var ArrayObject
	 */
	protected $_instanceConfig = null;
	/**
	 * ArrayObject that contains a list of inner ArrayObjects that hold default config params for registered classes.
	 * @var ArrayObject
	 */
	static $_defaultConfig = null;

	/**
	 * @param bool $overwriteDefault
	 *
	 * @throws Exception
	 */
	protected function __construct($overwriteDefault = false) {
		// check if child class implements $_configKey attribute
		if(!$this->is($this->_configKey)){
			throw new Exception('ClassAbstract: The child class must implement attribute "protected $_configKey = __CLASS__;"');
		}

		// create config tree
		$this->_createConfigTree();

		// set params
		$this->_overwriteDefault = $overwriteDefault;
	}

	/**
	 * Set or get a config param.
	 * If $value is null, function returns config value for the given $key.
	 * If $value is set, function stores a config value.
	 *
	 * @param      $key
	 * @param null $value
	 *
	 * @return mixed|$this
	 */
	protected function _config($key, $value = null) {
		if($this->isNull($value)) {
			return $this->_configGet($key);
		}

		$this->_configStore($key, $value);

		return $this;
	}

	/**
	 * Returns default config for current class.
	 *
	 * @return ArrayObject
	 */
	protected function _getDefaultConfig() {
		$this->_createConfigTree();

		return self::$_defaultConfig->key($this->_configKey);
	}

	/**
	 * Returns config for current instance.
	 *
	 * @return ArrayObject
	 */
	protected function _getInstanceConfig() {
		return $this->_instanceConfig;
	}

	/**
	 * Sets default config for current class.
	 *
	 * @param array|ArrayObject $defaultConfig
	 */
	protected function _setDefaultConfig($defaultConfig) {
		$this->_createConfigTree();

		// note: the default config can be set only one time
		if(self::$_defaultConfig->key($this->_configKey)->count() < 1) {
			self::$_defaultConfig->key($this->_configKey, new ArrayObject($defaultConfig));
		}

		// instance config must take over the default config params, else the system will not load the default data correctly.
		$this->_instanceConfig = clone self::$_defaultConfig->key($this->_configKey);
	}

	/**
	 * Validates and creates, if necessary, the config tree.
	 */
	private function _createConfigTree() {
		// check if default config is created
		if($this->isNull(self::$_defaultConfig)) {
			self::$_defaultConfig = new ArrayObject([]);
		}

		// check if default config is created for current class
		if(!self::$_defaultConfig->keyExists($this->_configKey)) {
			self::$_defaultConfig->key($this->_configKey, new ArrayObject([]));
		}

		// check instance config
		if($this->isNull($this->_instanceConfig)) {
			$this->_instanceConfig = new ArrayObject([]);
		}
	}

	/**
	 * Store a config param.
	 *
	 * @param string|int $key
	 * @param mixed      $value
	 */
	private function _configStore($key, $value) {
		if($this->_overwriteDefault) {
			$this->_getDefaultConfig()->key($key, $value);
		}

		$this->_getInstanceConfig()->key($key, $value);
	}

	/**
	 * Return config value for given $key.
	 * The config is first returned from the instance config. If the $key doesn't exist in instance config,
	 * default config is returned.
	 *
	 * @param string|int $key
	 *
	 * @throws Exception
	 * @return mixed
	 */
	private function _configGet($key) {
		if($this->_getInstanceConfig()->keyExists($key)) {
			return $this->_getInstanceConfig()->key($key);
		} else {
			if($this->_getDefaultConfig()->keyExists($key)) {
				return $this->_getDefaultConfig()->key($key);
			} else {
				throw new Exception('ConfigAbstract: Unable to find a value for key: ' . $key);
			}
		}
	}
}
