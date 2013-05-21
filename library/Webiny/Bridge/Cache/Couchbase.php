<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Bridge\Cache;

/**
 * Couchbase cache bridge loader.
 *
 * @package         Webiny\Bridge\Cache
 */
class Couchbase extends CacheAbstract
{

	/**
	 * Path to the default bridge library for APC.
	 *
	 * @var string
	 */
	static private $_library = '\Webiny\Bridge\Cache\Memory\Couchbase';

	/**
	 * Get the name of bridge library which will be used as the driver.
	 *
	 * @return string
	 */
	static function _getLibrary() {
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
	 * @param string           $cacheId         Cache identifier.
	 * @param \Couchbase       $couchbase       Instance of Couchbase class.
	 *
	 * @throws CacheException
	 * @return void|CacheInterface
	 */
	static function getInstance($cacheId, \Couchbase $couchbase = null) {
		$driver = static::_getLibrary();

		try {
			$instance = new $driver($couchbase, $cacheId);
		} catch (\Exception $e) {
			throw new CacheException($e->getMessage());
		}

		if(!self::isInstanceOf($instance, '\Webiny\Bridge\Cache\CacheInterface')) {
			throw new CacheException(CacheException::MSG_INVALID_ARG, [
																	  'driver',
																	  '\Webiny\Bridge\Cache\CacheInterface'
																	  ]);
		}

		return $instance;
	}
}