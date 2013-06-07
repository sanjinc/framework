<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Cache\Drivers;

use Webiny\Component\Cache\CacheDriver;
use Webiny\StdLib\ValidatorTrait;

/**
 * Memcache cache driver.
 *
 * @package         Webiny\Component\Cache
 */
class Memcache
{
	use ValidatorTrait;

	/**
	 * Get an instance of Memcache cache driver.
	 *
	 * @param string $cacheId    Cache identifier.
	 * @param string $host       Host on which memcached is running.
	 * @param int    $port       Port on which memcached is running.
	 * @param bool   $status     Cache status.
	 *
	 * @return CacheDriver
	 */
	static function getInstance($cacheId = '', $host = 'locahost', $port = 11211, $status = true) {
		$driver = \Webiny\Bridge\Cache\Memcache::getInstance($cacheId, $host, $port);

		return new CacheDriver($driver, $status);
	}
}