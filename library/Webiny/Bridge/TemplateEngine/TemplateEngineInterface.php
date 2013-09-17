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

/**
 * Template engine bridge interface.
 *
 * @package         Webiny\Bridge\TemplateEngine
 */

interface TemplateEngineInterface extends \Webiny\Component\TemplateEngine\TemplateEngineInterface
{

	/**
	 * Base constructor.
	 *
	 * @param ConfigObject $config Configuration for the template engine.
	 */
	function __construct(ConfigObject $config);

}