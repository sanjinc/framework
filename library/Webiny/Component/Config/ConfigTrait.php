<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright @ 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Config;

/**
 * A library of config functions
 *
 * @package         Webiny\Component\Config
 */
trait ConfigTrait
{
	/**
	 * Get Config object from INI file or string
	 *
	 * @param string $resource      Config resource in form of a file path or config string
	 *
	 * @param bool   $flushCache    Flush existing cache and load config file
	 *
	 * @param bool   $useSections
	 * @param string $nestDelimiter Delimiter for nested properties, ex: a.b.c or a-b-c
	 *
	 * @return ConfigObject
	 */
	protected static function getIniConfig($resource, $flushCache = false, $useSections = true, $nestDelimiter = '.') {
		return Config::Ini($resource, $flushCache, $useSections, $nestDelimiter);
	}

	/**
	 * Get Config object from JSON file or string
	 *
	 * @param string $resource      Config resource in form of a file path or config string
	 *
	 * @param bool   $flushCache    Flush existing cache and load config file
	 *
	 * @return ConfigObject
	 */
	protected static function getJsonConfig($resource, $flushCache = false) {
		return Config::Json($resource, $flushCache);
	}

	/**
	 * Get ConfigObject from YAML file or string
	 *
	 * @param string $resource      Config resource in form of a file path or config string
	 *
	 * @param bool   $flushCache    Flush existing cache and load config file
	 *
	 * @return ConfigObject
	 */
	protected static function getYamlConfig($resource, $flushCache = false) {
		return Config::Yaml($resource, $flushCache);
	}


	/**
	 * Get Config object from PHP file or array
	 *
	 * @param string|array $resource      Config resource in form of a file path or config string
	 *
	 * @param bool         $flushCache    Flush existing cache and load config file
	 *
	 * @return ConfigObject
	 */
	protected static function getPhpConfig($resource, $flushCache = false) {
		return Config::Php($resource, $flushCache);
	}

	/**
	 * Parse resource and create a Config object
	 * A valid resource is a PHP array, ArrayObject or an instance of DriverAbstract
	 *
	 * @param array|ArrayObject|DriverAbstract $resource   Config resource
	 * @param bool                             $flushCache Flush existing cache and load config file
	 *
	 * @return ConfigObject
	 */
	protected static function getCustomConfig($resource, $flushCache = false) {
		return Config::parseResource($resource, $flushCache);
	}
}