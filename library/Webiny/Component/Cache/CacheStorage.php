<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Cache;

use Webiny\Bridge\Cache\CacheStorageInterface;
use Webiny\Component\Cache\Storage\Null;

/**
 * CacheStorage is the main instance for working with cache drivers.
 * The best way to create a CacheStorage instance is over the Cache class.
 *
 * @package         Webiny\Component\Cache
 */

class CacheStorage
{
	/**
	 * @var null|Null
	 */
	static private $_nullDriver = null;

	/**
	 * @var \Webiny\Bridge\Cache\CacheStorageInterface
	 */
	private $_driver;

	/**
	 * @var array
	 */
	private $_options = [
		'status' => true,
		'ttl'    => 86400
	];


	/**
	 * Create a cache driver instance.
	 *
	 * @param CacheStorageInterface $driver  Instance of CacheInterface.
	 * @param array          $options Array of options.
	 */
	function __construct(CacheStorageInterface $driver, array $options = []) {
		$this->_driver = $driver;
		
		foreach ($options as $k => $v) {
			if(isset($this->_options[$k])) {
				$this->_options[$k] = $v;
			}
		}
	}

	/**
	 * Get driver instance.
	 *
	 * @return CacheStorageInterface
	 */
	function getDriver() {
		// if driver status is false, we return the Null driver
		if(!$this->getStatus()) {
			if(is_null(self::$_nullDriver)) {
				self::$_nullDriver = new Storage\Null();
			}

			return self::$_nullDriver;
		}

		return $this->_driver;
	}

	/**
	 * Get cache status.
	 *
	 * @return mixed
	 */
	function getStatus() {
		return $this->_options['status'];
	}

	/**
	 * Change the cache status.
	 *
	 * @param bool $status Turn caching on or off.
	 */
	function setStatus($status){
		$this->_options['status'] = (bool) $status;
	}

	/**
	 * Returns the ttl from options.
	 *
	 * @return int
	 */
	function getTtl() {
		return $this->_options['ttl'];
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
	public function add($key, $value, $ttl = null, $tags = null) {
		return $this->getDriver()->add($key, $value, (is_null($ttl) ? $this->getTtl() : $ttl), $tags);
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
	public function save($key, $value, $ttl = null, $tags = null) {
		return $this->getDriver()->save($key, $value, (is_null($ttl) ? $this->getTtl() : $ttl), $tags);
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