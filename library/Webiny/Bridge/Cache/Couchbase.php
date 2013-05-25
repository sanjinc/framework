<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Bridge\Cache;

use Webiny\Component\Cache\CacheException;

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
		if(isset(self::webiny()->getConfig()->bridges->cache->couchbase)){
			return self::webiny()->getConfig()->bridges->cache->couchbase;
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
	 * @see      CacheAbstract::getInstance()
	 *
	 * @param string $cacheId         Cache identifier.
	 * @param string $user Couchbase username.
	 * @param string $password Couchbase password.
	 * @param string $bucket Couchbase bucket.
	 * @param string $host Couchbase host (with port number).
	 *
	 * @throws \Webiny\Component\Cache\CacheException
	 * @internal param \Couchbase $couchbase Instance of Couchbase class.
	 *
	 * @return void|CacheInterface
	 */
	static function getInstance($cacheId, $user='', $password='', $bucket='', $host = '127.0.0.1:8091') {
		$driver = static::_getLibrary();

		// check if Couchbase extension is loaded
		if(!class_exists('Couchbase', true)) {
			throw new CacheException('The "Couchbase" SDK must be installed if you wish to use Couchbase.
										For more information visit: http://www.couchbase.com/develop/php/current');
		} else {
			$couchbase = new \Couchbase($host, $user, $password, $bucket);
		}

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