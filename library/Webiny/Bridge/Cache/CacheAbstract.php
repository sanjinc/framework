<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Bridge\Cache;

use \Webiny\StdLib\ValidatorTrait;

/**
 * Webiny cache bridge.
 *
 * @package         Webiny\Bridge\Cache;
 */
abstract class CacheAbstract
{
	use ValidatorTrait;

	/**
	 * Get the name of bridge library which will be used as the driver.
	 *
	 * @return string
	 */
	abstract static protected function _getLibrary();

	/**
	 * Change the default library used for the driver.
	 *
	 * @param string $pathToClass Path to the new driver class. Must be an instance of \Webiny\Bridge\Cache\CacheInterface
	 */
	abstract static public function setLibrary($pathToClass);

	/**
	 * Create an instance of a cache driver.
	 *
	 * @param $cacheId
	 *
	 * @return CacheInterface
	 * @throws CacheException
	 */
	static function getInstance($cacheId) {
		$driver = static::_getLibrary();

		try{
			$instance = new $driver($cacheId);
		}catch (\Exception $e){
			throw new CacheException($e->getMessage());
		}

		if(!self::isInstanceOf($instance, '\Webiny\Bridge\Cache\CacheInterface')){
			throw new CacheException(\CacheException::MSG_INVALID_ARG, ['driver', '\Webiny\Bridge\Cache\CacheInterface']);
		}

		return $instance;
	}

}