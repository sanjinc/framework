<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\TemplateEngine;

use Webiny\Bridge\TemplateEngine\TemplateEngine;
use Webiny\WebinyTrait;

/**
 * Creates instances of template engine drivers.
 *
 * @package         Webiny\Component\TemplateEngine
 */

class TemplateEngineLoader
{
	use WebinyTrait;

	static $_instances = [];

	/**
	 * Returns an instance of template engine driver.
	 * If the requested driver is already created, the same instance is returned.
	 *
	 * @param string $driver Name of the template engine driver. Must correspond to components.template_engine.engines.{$driver}.
	 *
	 * @return \Webiny\Bridge\TemplateEngine\TemplateEngineInterface
	 * @throws TemplateEngineException
	 * @throws \Exception
	 */
	static function getInstance($driver) {

		if(isset(self::$_instances[$driver])){
			return self::$_instances[$driver];
		}

		$driverConfig = self::webiny()->getConfig()->get('components.template_engine.engines.'.$driver, false);
		if(!$driverConfig){
			throw new TemplateEngineException('Unable to read driver configuration: components.template_engine.engines.'.$driver);
		}

		try{
			self::$_instances[$driver] = TemplateEngine::getInstance($driver, $driverConfig);
			return self::$_instances[$driver];
		}catch (\Exception $e){
			throw $e;
		}
	}

}