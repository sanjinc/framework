<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Cache;

use Jamm\Memory\KeyAutoUnlocker;

/**
 * Webiny cache component.
 *
 * The cache component is used to store different information into memory or file system for much faster access.
 * The stored information can only be accessed for a limited time, after which it is invalidated.
 *
 * This component is based on Memory library by Eugeniy Oz and as so is under an MIT licence.
 * @link https://github.com/jamm/Memory
 *
 * The Memory library is required by this class.
 *
 * @package		 WebinyFramework
 */
class Cache implements \Jamm\Memory\IMemoryStorage {

	const DRIVER_APC = 'APC';
	const DRIVER_COUCHBASE = 'Couchbase';
	const DRIVER_MEMCACHE = 'Memcache';
	const DRIVER_REDIS = 'Redis';
	const DRIVER_FILESYSTEM = 'FileSystem';

	private $_driverInstance = null;

	public function __construct($id = '', $driver=self::DRIVER_APC ){
		switch($driver){
			case 'APC':
				$this->_driverInstance = new \Jamm\Memory\APCObject($id);
				break;

			case 'Couchbase':
				$this->_driverInstance = new \Jamm\Memory\CouchbaseObject($id);
				break;

			case 'Memcache':
				$this->_driverInstance = new \Jamm\Memory\MemcacheObject($id);

			default:
				throw new \CacheException('The provided driver "'.$driver.'" is not supported.');
				break;
		}
	}

	/**
	 * Add value to memory storage, only if this key does not exists (or false will be returned).
	 *
	 * @param string       $key
	 * @param mixed        $value
	 * @param int          $ttl
	 * @param array|string $tags
	 *
	 * @return boolean
	 */
	public function add($key, $value, $ttl = 259200, $tags = null) {
		// TODO: Implement add() method.
	}

	/**
	 * Save variable in memory storage
	 *
	 * @param string       $key               - key
	 * @param mixed        $value             - value
	 * @param int          $ttl               - time to live (store) in seconds
	 * @param array|string $tags              - array of tags for this key
	 * @return bool
	 */
	public function save($key, $value, $ttl = 259200, $tags = null) {
		// TODO: Implement save() method.
	}

	/**
	 * Read data from memory storage
	 *
	 * @param string|array $key        (string or array of string keys)
	 * @param mixed        $ttl_left   = (ttl - time()) of key. Use to exclude dog-pile effect, with lock/unlock_key methods.
	 * @return mixed
	 */
	public function read($key, &$ttl_left = -1) {
		// TODO: Implement read() method.
	}

	/**
	 * Delete key or array of keys from storage
	 * @param string|array $key - keys
	 * @return boolean|array - if array of keys was passed, on error will be returned array of not deleted keys, or 'true' on success.
	 */
	public function del($key) {
		// TODO: Implement del() method.
	}

	/**
	 * Delete old (by ttl) variables from storage
	 * @return boolean
	 */
	public function del_old() {
		// TODO: Implement del_old() method.
	}

	/**
	 * Delete keys by tags
	 *
	 * @param array|string $tag - tag or array of tags
	 * @return boolean
	 */
	public function del_by_tags($tag) {
		// TODO: Implement del_by_tags() method.
	}

	/**
	 * Select from storage via callback function
	 * Only values of 'array' type will be selected
	 * @param callable $fx ($value_array,$key)
	 * @param bool     $get_array
	 * @return mixed
	 */
	public function select_fx($fx, $get_array = false) {
		// TODO: Implement select_fx() method.
	}

	/**
	 * Increment value of the key
	 * @param string $key
	 * @param mixed  $by_value
	 *                                 if stored value is an array:
	 *                                 if $by_value is a value in array, new element will be pushed to the end of array,
	 *                                 if $by_value is a key=>value array, new key=>value pair will be added (or updated)
	 * @param int    $limit_keys_count - maximum count of elements (used only if stored value is array)
	 * @param int    $ttl              - set time to live for key
	 * @return int|string|array new value of key
	 */
	public function increment($key, $by_value = 1, $limit_keys_count = 0, $ttl = 259200) {
		// TODO: Implement increment() method.
	}

	/**
	 * Get exclusive mutex for key. Key will be still accessible to read and write, but
	 * another process can exclude dog-pile effect, if before updating the key he will try to get this mutex.
	 * @param mixed $key
	 * @param mixed $auto_unlocker_variable - pass empty, just declared variable
	 */
	public function lock_key($key, &$auto_unlocker_variable) {
		// TODO: Implement lock_key() method.
	}

	/**
	 * Try to lock key, and if key is already locked - wait, until key will be unlocked.
	 * Time of waiting is defined in max_wait_unlock constant of MemoryObject class.
	 * @param string $key
	 * @param        $auto_unlocker
	 * @return boolean
	 */
	public function acquire_key($key, &$auto_unlocker) {
		// TODO: Implement acquire_key() method.
	}

	/**
	 * Unlock key, locked by method 'lock_key'
	 * @param KeyAutoUnlocker $auto_unlocker
	 * @return bool
	 */
	public function unlock_key(KeyAutoUnlocker $auto_unlocker) {
		// TODO: Implement unlock_key() method.
	}

	/**
	 * @return array of all stored keys
	 */
	public function get_keys() {
		// TODO: Implement get_keys() method.
	}

	/**
	 * @return string
	 */
	public function getLastErr() {
		// TODO: Implement getLastErr() method.
	}

	/**
	 * @return array
	 */
	public function get_stat() {
		// TODO: Implement get_stat() method.
	}

	public function getErrLog() {
		// TODO: Implement getErrLog() method.
	}

	public function set_errors_triggering($errors_triggering = true) {
		// TODO: Implement set_errors_triggering() method.
	}

	public function set_ID($ID) {
		// TODO: Implement set_ID() method.
	}

	public function get_ID() {
		// TODO: Implement get_ID() method.
	}}