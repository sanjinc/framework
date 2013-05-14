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
 * Cache APC driver.
 *
 * @package         Webiny\Component\Cache
 */
class APC
{
	use ValidatorTrait;

	/**
	 * Get an instance of APC cache driver.
	 *
	 * @param string $cacheId Cache identifier.
	 *
	 * @return \Webiny\Bridge\Cache\CacheInterface
	 */
	static function getInstance($cacheId = '') {
		return \Webiny\Bridge\Cache\APC::getInstance($cacheId);
	}
}