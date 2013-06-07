<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Http;

/**
 * HttpTrait give you access to Http components such as Request, Server, Session, Cookie, etc.
 *
 * @package		 Webiny\Component\Http
 */
 
trait HttpTrait{

	/**
	 * Get Request component instance.
	 *
	 * @return Request
	 */
	function request(){
		return Request::getInstance();
	}
}