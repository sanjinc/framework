<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny;

use Webiny\Bridge\Cache\CacheInterface;
use Webiny\Component\Cache\Cache;
use Webiny\Component\ClassLoader\ClassLoader;
use Webiny\Component\Config\Config;
use Webiny\StdLib\Exception\Exception;
use Webiny\StdLib\SingletonTrait;

/**
 * Webiny Framework Base is a staging class.
 *
 * This class reads the main config parameters and sets up the environment.
 * You can use this class directly, but the preferred way is to use the WebinyTrait inside your desired class and than
 * you can access Webiny Framework by a simple call to 'webiny' method.
 *
 * Example:
 * class MyClass{
 * 		use \Webiny\WebinyTrait;
 *
 * 		public function myMethod(){
 * 			$appPath = $this->webiny()->getAppPath();
 * 		}
 * }
 *
 * @package         Webiny
 */

class WebinyFrameworkBase
{

	use SingletonTrait;

	/**
	 * @var int Is the framework loaded or not.
	 */
	static private $_status = 0;

	/**
	 * @var Config Holds the aggregated config object.
	 */
	static private $_config;

	/**
	 * @var bool|Cache If available, holds the instance of system cache.
	 */
	static private $_cache = false;

	/**
	 * @var string Path to the Webiny framework folder. (with trailing slash)
	 */
	static private $_frameworkPath = '';

	/**
	 * @var string Path to the 'library' folder. (with trailing slash)
	 */
	static private $_libPath = '';

	/**
	 * @var string Path to the 'app' folder. (with trailing slash)
	 */
	static private $_appPath = '';

	/**
	 * @var string Path to the 'web' folder. (with trailing slash)
	 */
	static private $_webPath = '';

	/**
	 * Initialize Webiny framework.
	 * This function reads and aggregates all of the config files and setups the environment accordingly.
	 *
	 * @throws StdLib\Exception\Exception
	 */
	public function init() {

		if(self::$_status) {
			throw new Exception('Webiny framework is already initialized. You cannot initialize it again.');
		}

		$this->_parseConfigs();
		$this->_readEnvironment();

		$this->_setupErrorEnvironment();
		$this->_setupClassLoader();
		$this->_checkForSystemCache();
		$this->_assignCacheToClassLoader();

		self::$_status = 1;
	}

	/**
	 * @return bool|CacheInterface
	 */
	public function getCache() {
		return self::$_cache;
	}

	/**
	 * @return mixed
	 */
	public function getConfig() {
		return self::$_config;
	}

	/**
	 * Get the absolute path to the framework.
	 * NOTE: The path contains a trailing slash.
	 *
	 * @return string Absolute path to the framework with trailing slash.
	 */
	public function getFrameworkPath() {
		return self::$_frameworkPath;
	}

	/**
	 * Get the absolute path to the library folder.
	 * NOTE: The path contains a trailing slash.
	 *
	 * @return string Absolute path to the library folder with trailing slash.
	 */
	public function getLibraryPath() {
		return self::$_libPath;
	}

	/**
	 * Get the absolute path to the app folder.
	 * NOTE: The path contains a trailing slash.
	 *
	 * @return string Absolute path to the app folder with trailing slash.
	 */
	public function getAppPath() {
		return self::$_appPath;
	}

	/**
	 * Get the ABSOLUTE path to the web folder.
	 * NOTE: The path contains a trailing slash.
	 *
	 * @return string ABSOLUTE path to the web folder with trailing slash.
	 */
	public function getWebPath() {
		return self::$_webPath;
	}

	/**
	 * Read the system config and store it into registry
	 */
	private function _parseConfigs() {
		self::$_config = \Webiny\Component\Config\Config::Yaml(dirname(__FILE__) . '/webiny.yaml');
	}

	/**
	 * Assigns stuff like absolute path to the config.
	 */
	private function _readEnvironment() {
		self::$_frameworkPath = realpath(dirname(__FILE__)) . '/';
		self::$_libPath = realpath(self::$_frameworkPath . '../') . '/';
		self::$_appPath = realpath(self::$_frameworkPath . '../../app') . '/';
		self::$_webPath = realpath(self::$_frameworkPath . '../../web') . '/';
	}

	/**
	 * Checks if there is a cache defined in the config.
	 * If cache is defined, than its instance is stored as a static property of this class.
	 *
	 * @throws \Exception
	 */
	private function _checkForSystemCache() {
		if(isset(self::$_config->system->cache) && self::$_config->system->cache != '') {
			try {
				self::$_cache = call_user_func_array('Webiny\Component\Cache\Cache::' . self::$_config->system->cache->driver->name,
													 self::$_config->system->cache->driver->params->toArray());
			} catch (\Exception $e) {
				throw $e;
			}
		}
	}

	/**
	 * Setup the environment based on config params
	 */
	private function _setupErrorEnvironment() {
		if(self::$_config->system->display_errors == 'true') {
			error_reporting(E_ALL);
			ini_set('display_errors', '1');
		} else {
			error_reporting(0);
			ini_set('display_errors', '0');
		}
	}

	/**
	 * Register additional libraries to class loader.
	 */
	private function _setupClassLoader() {
		if(is_object(self::$_config->additional_libraries)) {
			// we must add the absolute path to the additional libraries
			$maps = self::$_config->additional_libraries->toArray();
			foreach ($maps as $k => $v) {
				$maps[$k] = self::$_frameworkPath . $v;
			}
			ClassLoader::getInstance()->registerMap($maps);
		}
	}

	/**
	 * Checks if a system cache is set.
	 * If the cache is set, than it is registered with the class loader.
	 */
	private function _assignCacheToClassLoader() {
		if(self::$_cache) {
			ClassLoader::getInstance()->registerCacheDriver(self::$_cache);
		}
	}
}