<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Bridge\Crypt;

use Webiny\StdLib\Exception\Exception;
use Webiny\StdLib\StdLibTrait;
use Webiny\WebinyTrait;

/**
 * Class that holds the bridge crypt instance
 *
 * @package         Webiny\Bridge\Crypt
 */

class Crypt
{
	use WebinyTrait, StdLibTrait;

	/**
	 * Path to the default bridge crypt library.
	 *
	 * @var string
	 */
	static private $_library = '\Webiny\Bridge\Crypt\CryptLib\CryptLib';

	/**
	 * Get the name of bridge library which will be used as the driver.
	 *
	 * @return string
	 */
	static function _getLibrary() {
		if(isset(self::webiny()->getConfig()->bridges->crypt)) {
			return self::webiny()->getConfig()->bridges->crypt;
		}

		return self::$_library;
	}

	/**
	 * Change the default library used for the driver.
	 *
	 * @param string $pathToClass Path to the new bridge class.
	 *                            The class must implement \Webiny\Bridge\Crypt\CryptInterface.
	 */
	static function setLibrary($pathToClass) {
		self::$_library = $pathToClass;
	}

	/**
	 * Create an instance of a crypt driver.
	 *
	 *
	 * @param $passwordAlgo
	 * @param $cipherMode
	 * @param $cipherBlock
	 * @param $cipherInitVector
	 *
	 * @throws \Webiny\StdLib\Exception\Exception
	 * @return CryptInterface
	 */
	static function getInstance($passwordAlgo, $cipherMode, $cipherBlock, $cipherInitVector) {
		$driver = static::_getLibrary();

		try {
			$instance = new $driver($passwordAlgo, $cipherMode, $cipherBlock, $cipherInitVector);
		} catch (\Exception $e) {
			throw new Exception('Unable to create an instance of '.$driver);
		}

		if(!self::isInstanceOf($instance, '\Webiny\Bridge\Crypt\CryptInterface')) {
			throw new Exception(Exception::MSG_INVALID_ARG, [
																	  'driver',
																	  '\Webiny\Bridge\Crypt\CryptInterface'
																	  ]);
		}

		return $instance;
	}
}
 
