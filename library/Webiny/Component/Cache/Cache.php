<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Cache;

use Webiny\Component\Cache\CacheException;

/**
 * Webiny cache component.
 *
 * The cache component is used to store different information into memory or file system for much faster access.
 * The stored information can only be accessed for a limited time, after which it is invalidated.
 *
 * This component is based on Memory library by Eugeniy Oz and as so is under an MIT licence.
 * @link            https://github.com/jamm/Memory
 *
 * The Memory library is required by this class.
 *
 * @package         WebinyFramework
 */
class Cache
{
	/**
	 * @var array Array of cache driver instances.
	 */
	static private $_driverInstances = [];

	/**
	 * Stores an instance of CacheDriver into the Cache object.
	 *
	 * @param CacheDriver $driver
	 *
	 * @throws CacheException
	 */
	static function addDriver(CacheDriver $driver) {
		if(isset(self::$_driverInstances[$driver->getId()])) {
			throw new CacheException('Unable to add a new cache driver. A driver with id "' . $driver->getId() . '"
										is already registered.');
		}

		self::$_driverInstances[$driver->getId()] = $driver;
	}

	/**
	 * Create a cache instance with APC as cache driver.
	 *
	 * @param string $cacheId  Cache identifier.
	 * @param array  $options  Cache options.
	 *
	 * @return CacheDriver
	 */
	static function APC($cacheId, array $options = []) {
		self::addDriver(Drivers\APC::getInstance($cacheId), $options);

		return self::getDriverInstance($cacheId);
	}

	/**
	 * Create a cache instance with Couchbase as cache driver.
	 *
	 * @param string $cacheId   Cache identifier.
	 * @param string $user      Couchbase username.
	 * @param string $password  Couchbase password.
	 * @param string $bucket    Couchbase bucket.
	 * @param string $host      Couchbase host (with port number).
	 * @param bool   $status    Cache status.
	 *
	 * @return CacheDriver
	 */
	static function Couchbase($cacheId, $user, $password, $bucket, $host = '127.0.0.1:8091', $status = true) {
		self::addDriver(Drivers\Couchbase::getInstance($cacheId, $user, $password, $bucket, $host), $status);

		return self::getDriverInstance($cacheId);
	}

	/**
	 * Create a cache instance with Memcache as cache driver.
	 *
	 * @param string $cacheId   Cache identifier.
	 * @param string $host      Host where the memcached is running.
	 * @param int    $port      Port where memcached is running.
	 * @param bool   $status    Cache status.
	 *
	 * @return CacheDriver
	 */
	static function Memcache($cacheId, $host = '127.0.0.1', $port = 11211, $status = true) {
		self::addDriver(Drivers\Memcache::getInstance($cacheId, $host, $port), $status);

		return self::getDriverInstance($cacheId);
	}

	/**
	 * Create a cache instance with Redis as cache driver.
	 *
	 * @param string $cacheId   Cache identifier.
	 * @param string $host      Host where the Redis server is running.
	 * @param int    $port      Port where Redis server is running.
	 * @param bool   $status    Cache status.
	 *
	 * @return CacheDriver
	 */
	static function Redis($cacheId, $host = '127.0.0.1', $port = 6379, $status = true) {
		self::addDriver(Drivers\Redis::getInstance($cacheId, $host, $port), $status);

		return self::getDriverInstance($cacheId);
	}

	/**
	 * Get driver instance for the given $cacheId.
	 *
	 * @param string $cacheId The identifier of the cache driver.
	 *
	 * @return CacheDriver
	 * @throws CacheException
	 */
	static public function getDriverInstance($cacheId) {
		if(!isset(self::$_driverInstances[$cacheId])) {
			throw new CacheException('Cache driver instance not found for the key "' . $cacheId . '".');
		}

		$driver = self::$_driverInstances[$cacheId];

		return $driver;
	}
}