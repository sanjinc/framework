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
 * Cache APC storage.
 *
 * @package         Webiny\Component\Cache
 */
class APC
{

	/**
	 * Get an instance of APC cache storage.
	 *
	 * @return CacheStorageInterface
	 */
	static function getInstance() {
		return \Webiny\Bridge\Cache\APC::getInstance();
	}
}