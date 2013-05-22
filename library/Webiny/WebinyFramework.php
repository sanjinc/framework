<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny;

use Webiny\Component\ClassLoader\ClassLoader;
use Webiny\Component\Registry\Registry;

/**
 * Webiny Framework staging class.
 *
 * This class reads the main config parameters and sets up the environment.
 *
 * @package		 Webiny
 */
 
class WebinyFramework{

	static private $_config;
	static private $_cache = false;

	static function init(){
		$static = new self;
		$static->_parseConfigs();
		$static->_readEnviroment();

		$static->_setupErrorEnvironment();
		$static->_setupClassLoader();
		$static->_checkForSystemCache();
		$static->_assignCacheToClassLoader();
		$static->_storeConfigToRegistry();
	}

	/**
	 * Read the system config and store it into registry
	 */
	private function _parseConfigs(){
		self::$_config = \Webiny\Component\Config\Config::Yaml(dirname(__FILE__).'/webiny.yaml');
	}

	/**
	 * Assigns stuff like absolute path to the config.
	 */
	private function _readEnviroment(){
		self::$_config->abs_path = realpath(dirname(__FILE__)).'/';
	}

	/**
	 * Checks if there is a cache defined in the config.
	 * If cache is defined, than its instance is stored as a static property of this class.
	 *
	 * @throws \Exception
	 */
	private function _checkForSystemCache(){
		if(isset(self::$_config->system->cache) && self::$_config->system->cache!=''){
			try{
				self::$_cache = call_user_func_array('Webiny\Component\Cache\Cache::'.self::$_config->system->cache->driver->name,
											  self::$_config->system->cache->driver->params->toArray());
			}catch (\Exception $e){
				throw $e;
			}
		}
	}

	/**
	 * Stores current config into the Registry.
	 */
	private function _storeConfigToRegistry(){
		Registry::getInstance()->webiny = self::$_config;
	}

	/**
	 * Setup the environment based on config params
	 */
	private function _setupErrorEnvironment(){
		if(self::$_config->system->display_errors=='true'){
			error_reporting(E_ALL);
			ini_set('display_errors', '1');
		}else{
			error_reporting(0);
			ini_set('display_errors', '0');
		}
	}

	/**
	 * Register additional libraries to class loader.
	 */
	private function _setupClassLoader(){
		if(is_object(self::$_config->additional_libraries)){
			// we must add the absolute path to the additional libraries
			$maps = self::$_config->additional_libraries->toArray();
			foreach($maps as $k=>$v){
				$maps[$k] = self::$_config->abs_path.$v;
			}
			ClassLoader::getInstance()->registerMap($maps);
		}
	}

	/**
	 * Checks if a system cache is set.
	 * If the cache is set, than it is registered with the class loader.
	 */
	private function _assignCacheToClassLoader(){
		if(self::$_cache){
			ClassLoader::getInstance()->registerCacheDriver(self::$_cache);
		}
	}
}