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
 * Couchbase cache driver.
 *
 * @package         Webiny\Component\Cache
 */
class Couchbase
{
	use ValidatorTrait;

	/**
	 * Get an instance of Couchbase cache driver.
	 *
	 * @param string $cacheId   Cache identifier.
	 * @param string $user      Couchbase username.
	 * @param string $password  Couchbase password.
	 * @param string $bucket    Couchbase bucket.
	 * @param string $host      Couchbase host (with port number).
	 *
	 * @return \Webiny\Bridge\Cache\CacheInterface
	 */
	static function getInstance($cacheId, $user, $password, $bucket, $host) {
		return \Webiny\Bridge\Cache\Couchbase::getInstance($cacheId, $user, $password, $bucket, $host);
	}
}