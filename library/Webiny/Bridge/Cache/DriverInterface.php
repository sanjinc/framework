<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Bridge\Cache;

/**
 * Webiny cache bridge driver interface.
 *
 * @package         Webiny\Bridge\Cache
 */

interface DriverInterface
{
	/**
	 * Get the name of bridge library which will be used as the driver.
	 *
	 * @return string
	 */
	static function _getLibrary();

	/**
	 * Change the default library used for the driver.
	 *
	 * @param string $pathToClass Path to the new driver class. Must be an instance of \Webiny\Bridge\Cache\CacheInterface
	 */
	static function setLibrary($pathToClass);
}