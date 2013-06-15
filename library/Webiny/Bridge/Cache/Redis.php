<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Bridge\Cache;

use Webiny\WebinyTrait;

/**
 * Redis cache bridge loader.
 *
 * @package         Webiny\Bridge\Cache
 */
class Redis extends CacheAbstract
{
	use WebinyTrait;

	/**
	 * Path to the default bridge library for APC.
	 *
	 * @var string
	 */
	static private $_library = '\Webiny\Bridge\Cache\Memory\Redis';

	/**
	 * Get the name of bridge library which will be used as the driver.
	 *
	 * @return string
	 */
	static function _getLibrary() {
		if(isset(self::webiny()->getConfig()->bridges->cache->redis)){
			return self::webiny()->getConfig()->bridges->cache->redis;
		}

		return self::$_library;
	}

	/**
	 * Change the default library used for the driver.
	 *
	 * @param string $pathToClass Path to the new driver class. Must be an instance of \Webiny\Bridge\Cache\CacheInterface
	 */
	static function setLibrary($pathToClass) {
		self::$_library = $pathToClass;
	}

	/**
	 * Override the CacheAbstract::getInstance method.
	 *
	 * @see CacheAbstract::getInstance()
	 *
	 * @param string       $host       Host on which Redis server is running.
	 * @param int          $port       Port on which Redis server is running.
	 *
	 * @throws CacheException
	 * @return void|CacheStorageInterface
	 */
	static function getInstance($host = '127.0.0.1', $port = 6379) {
		$driver = static::_getLibrary();

		try {
			$instance = new $driver($host, $port);
		} catch (\Exception $e) {
			throw new CacheException($e->getMessage());
		}

		if(!self::isInstanceOf($instance, '\Webiny\Bridge\Cache\CacheStorageInterface')) {
			throw new CacheException(CacheException::MSG_INVALID_ARG, [
																	  'driver',
																	  '\Webiny\Bridge\Cache\CacheStorageInterface'
																	  ]);
		}

		return $instance;
	}
}