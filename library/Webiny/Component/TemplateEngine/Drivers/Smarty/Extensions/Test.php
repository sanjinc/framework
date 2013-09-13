<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\TemplateEngine\Drivers\Smarty\Extensions;

use Webiny\Component\TemplateEngine\Drivers\Smarty\SmartyExtension;
use Webiny\Component\TemplateEngine\Drivers\Smarty\SmartySimplePlugin;

/**
 * Description
 *
 * @package		 Webiny\
 */
 
class Test extends SmartyExtension
{

	function getModifiers(){
		return [
			new SmartySimplePlugin('custom_upper', 'modifier', [$this, 'customUpper'])
		];
	}

	function customUpper($params){
		return strtoupper($params);
	}

	/**
	 * Returns the name of the plugin.
	 *
	 * @return string
	 */
	function getName() {
		return 'TEst';
	}
}