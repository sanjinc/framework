<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Cache;

/**
 * Trait for the Cache library.
 *
 * Usage example;
 * $this->cache('my_cache')->read('cache-key');
 *
 * @package         Webiny\Component\Cache
 */

trait Cache
{
	/**
	 * Returns an instance of cache driver for the given cache identifier.
	 *
	 * @param $cacheId
	 *
	 * @return \Webiny\Bridge\Cache\CacheInterface
	 */
	protected function cache($cacheId) {
		return Cache::getDriverInstance($cacheId);
	}
}