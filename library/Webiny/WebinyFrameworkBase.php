<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny;

use Webiny\Component\Cache\CacheStorage;
use Webiny\Component\Cache\CacheTrait;
use Webiny\Component\ClassLoader\ClassLoader;
use Webiny\Component\Config\Config;
use Webiny\Component\Config\ConfigCache;
use Webiny\Component\Config\ConfigObject;
use Webiny\Component\Logger\LoggerTrait;
use Webiny\Component\Security\Security;
use Webiny\Component\ServiceManager\ServiceManager;
use Webiny\Component\ServiceManager\ServiceManagerException;
use Webiny\Component\StdLib\Exception\Exception;
use Webiny\Component\StdLib\SingletonTrait;

/**
 * Webiny Framework Base is a staging class.
 *
 * This class reads the main config parameters and sets up the environment.
 * You can use this class directly, but the preferred way is to use the WebinyTrait inside your desired class and than
 * you can access Webiny Framework by a simple call to 'webiny' method.
 *
 * Example:
 * class MyClass{
 *        use \Webiny\WebinyTrait;
 *
 *        public function myMethod(){
 *            $appPath = $this->webiny()->getAppPath();
 *        }
 * }
 *
 * @package         Webiny
 */

class WebinyFrameworkBase
{
	use SingletonTrait, CacheTrait, LoggerTrait;

	/**
	 * @var int Is the framework loaded or not.
	 */
	static private $_status = 0;

	/**
	 * @var Config Holds the aggregated config object.
	 */
	static private $_config;

	/**
	 * @var Security
	 */
	static private $_security;

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

		self::$_status = 1;

		// config and environment
		$this->_parseConfigs();
		$this->_readEnvironment();

		// system core
		$this->_setupErrorEnvironment();
		$this->_setupClassLoader();
		$this->_setupSystemLogger();
		$this->_checkForCache();
		$this->_assignCacheToClassLoader();

		// initialize other components
		$this->_setupSecurityLayer();
	}

	/**
	 * @return ConfigObject
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
		$configPath = dirname(__FILE__) . '/webiny.yaml';
		$parsedConfigPath = dirname(__FILE__) . '/webiny.yaml.parsed';
		if(file_exists($parsedConfigPath)) {
			self::$_config = unserialize(file_get_contents($parsedConfigPath));
		} else {
			self::$_config = Config::getInstance()->yaml($configPath);
			file_put_contents($parsedConfigPath, serialize(self::$_config));
		}
		ConfigCache::setCache(ConfigCache::createCacheKey($configPath), self::$_config);
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
	 *
	 * @throws \Exception
	 */
	private function _checkForCache() {
		try {
			// we don't have to assign the cache anywhere, it will be stored in global cache registry
			ServiceManager::getInstance()->getService('cache.' . WF::CACHE);
		} catch (ServiceManagerException $e) {
			// system cache is not defined => omit the exception
		}
	}

	/**
	 * Setup the environment based on config params
	 */
	private function _setupErrorEnvironment() {
		if(self::$_config->system->display_errors) {
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
			$maps = self::$_config->additional_libraries->toArray();
			ClassLoader::getInstance()->registerMap($maps);
		}
	}

	/**
	 * Creates an instance of system logger.
	 */
	private function _setupSystemLogger() {
		try {
			$this->logger(WF::LOGGER)->info('System up and running');
		} catch (\Exception $e) {
			// ignore
		}
	}

	/**
	 * Tries to get the system cache driver and pass it to ClassLoader component.
	 */
	private function _assignCacheToClassLoader() {
		try {
			$cache = $this->cache(WF::CACHE);
			ClassLoader::getInstance()->registerCacheDriver($cache);
		} catch (\Exception $e) {
			// ignore
		}
	}

	/**
	 * Initializes the security layer.
	 * NOTE: This initialization might trigger a redirect.
	 */
	private function _setupSecurityLayer() {
		if(isset(self::$_config->security)) {
			self::$_security = Security::getInstance();
		}
	}
}