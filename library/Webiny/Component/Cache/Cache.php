<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Cache;

use Webiny\Bridge\Cache\CacheAbstract;
use Webiny\Bridge\Cache\CacheInterface;
use Webiny\Component\Cache\CacheException;
use Webiny\StdLib\StdLibTrait;

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
	use StdLibTrait;

	const DRIVER_APC = 'APC';
	const DRIVER_COUCHBASE = 'Couchbase';
	const DRIVER_MEMCACHE = 'Memcache';
	const DRIVER_REDIS = 'Redis';
	const DRIVER_FILESYSTEM = 'FileSystem';

	private $_cacheId = '';
	static private $_driverInstances = [];

	public function __construct(CacheInterface $driver, $cacheId) {
		if($this->is(self::$_driverInstances[$cacheId])){
			throw new CacheException('Another cache instance has already registered under the given cache id "'.$cacheId.'".');
		}

		self::$_driverInstances[$cacheId] = $driver;
		$this->_cacheId = $cacheId;
	}

	/**
	 * Create a cache instance with APC as cache driver.
	 *
	 * @param string $cacheId Cache identifier.
	 *
	 * @return Cache
	 */
	static function APC($cacheId) {
		$driver = Drivers\APC::getInstance($cacheId);

		return new static($driver, $cacheId);
	}

	/**
	 * Create a cache instance with Couchbase as cache driver.
	 *
	 * @param string $cacheId   Cache identifier.
	 * @param string $user      Couchbase username.
	 * @param string $password  Couchbase password.
	 * @param string $bucket    Couchbase bucket.
	 * @param string $host      Couchbase host (with port number).
	 *
	 * @return Cache
	 */
	static function Couchbase($cacheId, $user, $password, $bucket, $host = '127.0.0.1:8091') {
		$driver = Drivers\Couchbase::getInstance($cacheId, $user, $password, $bucket, $host);

		return new static($driver, $cacheId);
	}

	/**
	 * Create a cache instance with Memcache as cache driver.
	 *
	 * @param string $cacheId Cache identifier.
	 * @param string $host    Host where the memcached is running.
	 * @param int    $port    Port where memcached is running.
	 *
	 * @return Cache
	 */
	static function Memcache($cacheId, $host = '127.0.0.1', $port = 11211) {
		$driver = Drivers\Memcache::getInstance($cacheId, $host, $port);

		return new static($driver, $cacheId);
	}

	/**
	 * Create a cache instance with Redis as cache driver.
	 *
	 * @param string $cacheId Cache identifier.
	 * @param string $host    Host where the Redis server is running.
	 * @param int    $port    Port where Redis server is running.
	 *
	 * @return Cache
	 */
	static function Redis($cacheId, $host = '127.0.0.1', $port = 6379) {
		$driver = Drivers\Redis::getInstance($cacheId, $host, $port);

		return new static($driver, $cacheId);
	}

	/**
	 * Get driver instance for the given $cacheId.
	 *
	 * @param string $cacheId The identifier of the cache driver.
	 *
	 * @return CacheInterface
	 * @throws CacheException
	 */
	static public function getDriverInstance($cacheId){
		if(!self::is(self::$_driverInstances[$cacheId])){
			throw new CacheException('Cache driver instance not found for the key "'.$cacheId.'".');
		}

		return self::$_driverInstances[$cacheId];
	}

	/**
	 * Get current driver instance.
	 *
	 * @return null|CacheInterface
	 */
	public function getDriver() {
		return self::getDriverInstance($this->_cacheId);
	}

	/**
	 * Save a value into memory only if it DOESN'T exists (or false will be returned).
	 *
	 * @param string       $key    Name of the key.
	 * @param mixed        $value  Value you wish to save.
	 * @param int          $ttl    For how long to store value. (in seconds)
	 * @param array|string $tags   Tags you wish to assign to this cache entry.
	 *
	 * @return boolean True if value was added, otherwise false.
	 */
	public function add($key, $value, $ttl = 600, $tags = null) {
		return $this->getDriver()->add($key, $value, $ttl, $tags);
	}

	/**
	 * Save a value into memory.
	 *
	 * @param string       $key    Name of the key.
	 * @param mixed        $value  Value you wish to save.
	 * @param int          $ttl    For how long to store value. (in seconds)
	 * @param array|string $tags   Tags you wish to assign to this cache entry.
	 *
	 * @return bool True if value was stored successfully, otherwise false.
	 */
	public function save($key, $value, $ttl = 600, $tags = null) {
		return $this->getDriver()->save($key, $value, $ttl, $tags);
	}

	/**
	 * Get the cache data for the given $key.
	 *
	 * @param string|array $key        Name of the cache key.
	 * @param mixed        $ttlLeft    = (ttl - time()) of key. Use to exclude dog-pile effect, with lock/unlock_key methods.
	 *
	 * @return mixed
	 */
	public function read($key, &$ttlLeft = -1) {
		return $this->getDriver()->read($key, $ttlLeft);
	}

	/**
	 * Delete key or array of keys from storage.
	 *
	 * @param string|array $key Key, or array of keys, you wish to delete.
	 *
	 * @return boolean|array If array of keys was passed, on error will be returned array of not deleted keys, or true on success.
	 */
	public function delete($key) {
		return $this->getDriver()->del($key);
	}

	/**
	 * Delete expired cache values.
	 *
	 * @return boolean
	 */
	public function deleteOld() {
		return $this->getDriver()->del_old();
	}

	/**
	 * Delete keys by tags.
	 *
	 * @param array|string $tag Tag, or an array of tags, for which you wish to delete the cache.
	 *
	 * @return boolean
	 */
	public function deleteByTags($tag) {
		return $this->getDriver()->del_by_tags($tag);
	}

	/**
	 * Select from storage via callback function.
	 * Only values of array type will be selected.
	 *
	 * @param callable $callback ($value_array,$key)
	 * @param bool     $getArray
	 *
	 * @return mixed
	 */
	public function selectByCallback($callback, $getArray = false) {
		return $this->getDriver()->select_fx($callback, $getArray);
	}

	/**
	 * Increment value of the key.
	 *
	 * @param string $key              Name of the cache key.
	 * @param mixed  $byValue
	 *                                 If stored value is an array:
	 *                                  - If $by_value is a value in array, new element will be pushed to the end of array,
	 *                                  - If $by_value is a key=>value array, new key=>value pair will be added (or updated).
	 * @param int    $limitKeysCount   Maximum count of elements (used only if stored value is array).
	 * @param int    $ttl              Set time to live for key.
	 *
	 * @return int|string|array New key value.
	 */
	public function increment($key, $byValue = 1, $limitKeysCount = 0, $ttl = 259200) {
		return $this->getDriver()->increment($key, $byValue, $limitKeysCount, $ttl);
	}

	/**
	 * Get exclusive mutex for key. Key will be still accessible to read and write, but
	 * another process can exclude dog-pile effect, if before updating the key he will try to get this mutex.
	 *
	 * @param mixed $key                    Name of the cache key.
	 * @param mixed $autoUnlockerVariable   Pass empty, just declared variable
	 *
	 * @return bool
	 */
	public function lockKey($key, &$autoUnlockerVariable) {
		return $this->getDriver()->lock_key($key, $autoUnlockerVariable);
	}

	/**
	 * Try to lock key, and if key is already locked - wait, until key will be unlocked.
	 * Time of waiting is defined in max_wait_unlock constant of MemoryObject class.
	 *
	 * @param string $key Name of the cache key.
	 * @param        $autoUnlocker
	 *
	 * @return boolean
	 */
	public function acquireKey($key, &$autoUnlocker) {
		return $this->getDriver()->acquire_key($key, $autoUnlocker);
	}
}