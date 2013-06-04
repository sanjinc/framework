<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Cache\Drivers;

use Webiny\StdLib\ValidatorTrait;

/**
 * Redis cache driver.
 *
 * @package         Webiny\Component\Cache
 */
class Redis
{
	use ValidatorTrait;

	/**
	 * Get an instance of Redis cache driver.
	 *
	 * @param string $cacheId    Cache identifier.
	 * @param string $host       Host on which Redis server is running.
	 * @param int    $port       Port on which Redis server is running.
	 * @param bool   $status     Cache status.
	 *
	 * @return \Webiny\Bridge\Cache\CacheInterface
	 */
	static function getInstance($cacheId = '', $host = 'locahost', $port = 6379, $status = false) {
		$driver = \Webiny\Bridge\Cache\Redis::getInstance($cacheId, $host, $port);

		return new CacheDriver($driver, $status);
	}
}