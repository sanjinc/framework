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
use Webiny\Component\StdLib\ValidatorTrait;

/**
 * Couchbase cache storage.
 *
 * @package         Webiny\Component\Storage
 */
class Couchbase
{
	use ValidatorTrait;

	/**
	 * Get an instance of Couchbase cache storage.
	 *
	 * @param string $user       Couchbase username.
	 * @param string $password   Couchbase password.
	 * @param string $bucket     Couchbase bucket.
	 * @param string $host       Couchbase host (with port number).
	 *
	 * @return CacheStorageInterface
	 */
	static function getInstance($user, $password, $bucket, $host) {
		return \Webiny\Bridge\Cache\Couchbase::getInstance($user, $password, $bucket, $host);
	}
}