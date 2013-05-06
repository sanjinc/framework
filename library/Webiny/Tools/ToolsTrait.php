<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 * @package   WebinyFramework
 */

namespace Webiny\Tools;

/**
 * Trait that holds common tools methods.
 *
 * @package         WebinyFramework
 * @category        Tools
 */

trait ToolsTrait
{
	/**
	 * Redirects you to the given url.
	 * If params are defined, they are attached to the http request.
	 * Redirect will be issued either over header-location or over Curl, depending on request params.
	 *
	 * @param string            $url            Url address to which you want to redirect. If the address doesn't start
	 *                                          with http(s), the current host will be set as root.
	 * @param array|null|string $header         Array containing request params that will be appended to the GET request.
	 */
	public function redirect($url, $header) {
		\Webiny\Tools\Redirect\Redirect::redirect($url, $header);
	}

	/**
	 * Appends or prepends a new autoload function.
	 *
	 * @param        $functionName
	 * @param bool   $prepend                 Do you want to prepend or append the new auto load function
	 *                                        to the current stack.
	 *
	 * @internal param string $autoLoadFunctionName Name of the function that will be called when a class needs to load.
	 * @return bool
	 */
	public function registerAutoloader($functionName, $prepend = false) {
		return \Webiny\Tools\AutoLoader\AutoLoader::getInstance()->registerAutoloader($functionName, $prepend);
	}
}