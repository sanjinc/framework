<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Bridge\Cache;

use Webiny\StdLib\ValidatorTrait;
use Webiny\WebinyFrameworkBase;
use Webiny\WebinyTrait;

/**
 * APC cache bridge loader.
 *
 * @package         Webiny\Bridge\Cache
 */
class APC extends CacheAbstract
{
	use WebinyTrait;

	/**
	 * Path to the default bridge library for APC.
	 *
	 * @var string
	 */
	static private $_library = '\Webiny\Bridge\Cache\Memory\APC';

	/**
	 * Get the name of bridge library which will be used as the driver.
	 *
	 * @return string
	 */
	static function _getLibrary() {
		if(isset(self::webiny()->getConfig()->bridges->cache->apc)){
			return self::webiny()->getConfig()->bridges->cache->apc;
		}

		return self::$_library;
	}

	/**
	 * Change the default library used for the driver.
	 *
	 * @param string $pathToClass Path to the new driver class. Must be an instance of \Webiny\Bridge\Cache\CacheInterface
	 */
	static function setLibrary($pathToClass) {
		self::$_library = $pathToClass;
	}

}