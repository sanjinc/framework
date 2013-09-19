<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Bridge\Image;

use Webiny\Component\Config\ConfigObject;
use Webiny\Component\StdLib\FactoryLoaderTrait;
use Webiny\Component\StdLib\StdLibTrait;
use Webiny\WebinyTrait;


/**
 * Image bridge loader.
 *
 * @package		 Webiny\Bridge\Image
 */
 
class Loader{
	use WebinyTrait, FactoryLoaderTrait, StdLibTrait;

	/**
	 * @var string Default Image bridge.
	 */
	static private $_library = '\Webiny\Bridge\Image\Imagine\Imagine';


	/**
	 * Returns an instance of ImageLoaderInterface based on current bridge.
	 *
	 * @param ConfigObject $config
	 *
	 * @throws ImageException
	 *
	 * @return \Webiny\Component\Image\ImageLoaderInterface
	 */
	static function getImageLoader(ConfigObject $config) {
		$lib = self::_getLibrary();

		/** @var ImageLoaderInterface $libInstance */
		$instance = self::factory($lib, '\Webiny\Bridge\Image\ImageLoaderInterface', [$config]);

		if(!self::isInstanceOf($instance, '\Webiny\Bridge\Image\ImageLoaderInterface')) {
			throw new ImageException('The message library must implement "\Webiny\Bridge\Image\ImageLoaderInterface".');
		}

		return $instance;
	}


	/**
	 * Get the name of bridge library which will be used as the driver.
	 *
	 * @return string
	 */
	static function _getLibrary() {
		return self::webiny()->getConfig()->get('bridges.image', self::$_library);
	}

	/**
	 * Change the default library used for the driver.
	 *
	 * @param string $pathToClass Path to the new driver class. Must be an instance of \Webiny\Bridge\Image\ImageLoaderInterface
	 */
	static function setLibrary($pathToClass) {
		self::$_library = $pathToClass;
	}
}