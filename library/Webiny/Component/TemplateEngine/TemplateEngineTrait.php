<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\TemplateEngine;

/**
 * TemplateEngineTrait provides easier access to template engine methods.
 *
 * @package         Webiny\Component\TemplateEngine
 */

trait TemplateEngineTrait
{

	/**
	 * Get template engine instance.
	 *
	 * @param string $driver Name of the driver. Default driver is 'smarty'.
	 *
	 * @return \Webiny\Bridge\TemplateEngine\TemplateEngineInterface
	 */
	function templateEngine($driver = 'smarty'){
		return TemplateEngineLoader::getInstance($driver);
	}

}