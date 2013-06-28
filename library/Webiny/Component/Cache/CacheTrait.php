<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Cache;

use Webiny\Component\Cache\Storage\Null;
use Webiny\Component\ServiceManager\ServiceManager;
use Webiny\Component\ServiceManager\ServiceManagerException;
use Webiny\WF;

/**
 * Cache trait.
 * The cache trait uses WebinyFrameworkBase since the framework stores th
 *
 * @package         Webiny\
 */

trait CacheTrait
{

	static private $_nullStorage = null;

	/**
	 * Returns instance of cache driver.
	 * If instance with the given $cacheId doesn't exist, CacheException is thrown.
	 *
	 * @param string $cacheId Name of the cache driver
	 *
	 * @throws \Exception|ServiceManagerException
	 * @return CacheStorage
	 */
	function cache($cacheId = WF::CACHE) {
		try {
			return ServiceManager::getInstance()->getService('cache.' . $cacheId);
		} catch (ServiceManagerException $e) {
			if($e->getCode() == ServiceManagerException::SERVICE_DEFINITION_NOT_FOUND) {
				if(is_null(self::$_nullStorage)) {
					self::$_nullStorage = new Null();
				}

				return self::$_nullStorage;
			}

			throw $e;
		}
	}
}