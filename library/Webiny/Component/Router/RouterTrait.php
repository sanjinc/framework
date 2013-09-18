<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Router;

use Webiny\WebinyFrameworkBase;

/**
 * RouterTrait provides easier access to the Router component.
 *
 * @package         Webiny\Component\Router
 */

trait RouterTrait
{
	static private $_wfRouter = false;

	/**
	 * Get the Router instance.
	 * The returned Router instance uses routes defined inside your config as the default RouteCollection.
	 *
	 * @return Router
	 */
	function router(){
		if(!self::$_wfRouter){
			self::$_wfRouter = new Router(WebinyFrameworkBase::getInstance()->getConfig()->get('routes'));
		}

		return self::$_wfRouter;
	}

}