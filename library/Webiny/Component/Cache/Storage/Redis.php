<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Cache\Storage;

use Webiny\Bridge\Cache\CacheStorageInterface;
use Webiny\StdLib\ValidatorTrait;

/**
 * Redis cache storage.
 *
 * @package         Webiny\Component\Cache
 */
class Redis
{
	use ValidatorTrait;

	/**
	 * Get an instance of Redis cache storage.
	 *
	 * @param string $host        Host on which Redis server is running.
	 * @param int    $port        Port on which Redis server is running.
	 *
	 * @return CacheStorageInterface
	 */
	static function getInstance($host = 'locahost', $port = 6379) {
		return \Webiny\Bridge\Cache\Redis::getInstance($host, $port);
	}
}