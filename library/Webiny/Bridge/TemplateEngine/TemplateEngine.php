<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Bridge\TemplateEngine;

use Webiny\Component\Config\ConfigObject;
use Webiny\Component\StdLib\StdLibTrait;
use Webiny\WebinyTrait;

/**
 * This class creates instances of bridge drivers.
 *
 * @package         Webiny\Bridge\TemplateEngine
 */

class TemplateEngine
{
	use StdLibTrait, WebinyTrait;

	/**
	 * @var string Default TemplateEngine bridge library.
	 */
	static private $_library = ['smarty' => '\Webiny\Bridge\TemplateEngine\Smarty\Smarty'];

	/**
	 * Get the name of bridge library which will be used as the driver.
	 *
	 * @param string $engineName Name of the template engine for which you wish to get the
	 *
	 * @return string
	 */
	static function _getLibrary($engineName) {
		return self::webiny()->getConfig()->get('bridges.template_engine.' . $engineName, self::$_library[$engineName]);
	}

	/**
	 * Change the default library used for the driver.
	 *
	 * @param string $engineName  Name of the template engine for which to set the bridge library.
	 * @param string $pathToClass Path to the new driver class. Must be an instance of \Webiny\Bridge\Cache\CacheInterface
	 */
	static function setLibrary($engineName, $pathToClass) {
		self::$_library[$engineName] = $pathToClass;
	}

	/**
	 * Create an instance of an TemplateEngine driver.
	 *
	 * @param string                                $engineName Name of the template engine for which to load the instance.
	 * @param \Webiny\Component\Config\ConfigObject $config     Template engine config.
	 *
	 * @throws TemplateEngineException
	 * @throws \Exception
	 * @return TemplateEngineInterface
	 */
	static function getInstance($engineName, ConfigObject $config) {
		$driver = static::_getLibrary($engineName);
		if(!self::isString($driver)) {
			throw new TemplateEngineException('Invalid driver returned for ' . $engineName . ' engine');
		}

		try {
			$instance = new $driver($config);
		} catch (\Exception $e) {
			throw $e;
		}

		if(!self::isInstanceOf($instance, '\Webiny\Bridge\TemplateEngine\TemplateEngineInterface')) {
			throw new TemplateEngineException(Exception::MSG_INVALID_ARG, [
																		  'driver',
																		  '\Webiny\Bridge\TemplateEngine\TemplateEngineInterface'
																		  ]);
		}

		return $instance;
	}
}