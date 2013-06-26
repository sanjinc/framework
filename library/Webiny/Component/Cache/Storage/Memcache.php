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
use Webiny\Component\Cache\CacheStorage;
use Webiny\StdLib\ValidatorTrait;

/**
 * Memcache cache storage.
 *
 * @package         Webiny\Component\Cache
 */
class Memcache
{
	use ValidatorTrait;

	/**
	 * Get an instance of Memcache cache storage.
	 *
	 * @param string $host        Host on which memcached is running.
	 * @param int    $port        Port on which memcached is running.
	 *
	 * @return CacheStorageInterface
	 */
	static function getInstance($host = 'locahost', $port = 11211) {
		return \Webiny\Bridge\Cache\Memcache::getInstance($host, $port);
	}
}