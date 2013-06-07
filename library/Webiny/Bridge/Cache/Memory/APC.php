<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Bridge\Cache\Memory;

use Jamm\Memory\APCObject;
use Webiny\Bridge\Cache\CacheInterface;

/**
 * Bridge to APC cache libraries.
 *
 * @package         Webiny\Bridge\Cache
 */
class APC extends APCObject implements CacheInterface
{
	/**
	 * Delete key or array of keys from storage.
	 *
	 * @param string|array $key Key, or array of keys, you wish to delete.
	 *
	 * @return boolean|array If array of keys was passed, on error will be returned array of not deleted keys, or true on success.
	 */
	public function delete($key) {
		return $this->del($key);
	}

	/**
	 * Delete expired cache values.
	 *
	 * @return boolean
	 */
	public function deleteOld() {
		return $this->del_old();
	}

	/**
	 * Delete keys by tags.
	 *
	 * @param array|string $tag Tag, or an array of tags, for which you wish to delete the cache.
	 *
	 * @return boolean
	 */
	public function deleteByTags($tag) {
		return $this->del_by_tags($tag);
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
		return $this->select_fx($callback, $getArray);
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
		return $this->lock_key($key, $autoUnlockerVariable);
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
		return $this->acquire_key($key, $autoUnlocker);
	}

	/**
	 * Get cache id.
	 *
	 * @return string Cache id.
	 */
	public function getCacheId() {
		return $this->get_ID();
	}
}