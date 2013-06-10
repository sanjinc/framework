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
 * Cache trait.
 * The cache trait uses WebinyFrameworkBase since the framework stores th
 *
 * @package		 Webiny\
 */
 
trait CacheTrait{

	/**
	 * Returns instance of cache driver.
	 * If instance with the given $cacheId doesn't exist, CacheException is thrown.
	 *
	 * @param string $cacheId Name of the cache driver
	 *
	 * @throws \Exception|CacheException
	 * @return CacheDriver
	 */
	function cache($cacheId = 'wfc'){
		try{
			return Cache::getDriverInstance($cacheId);
		}catch (CacheException $e){
			throw $e;
		}
	}
}