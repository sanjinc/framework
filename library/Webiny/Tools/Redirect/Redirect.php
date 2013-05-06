<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 * @package   WebinyFramework
 */

namespace Webiny\Tools\Redirect;

use Webiny\StdLib\StdObject\StdObjectWrapper;
use Webiny\StdLib\StdObject\StringObject\StringObject;
use Webiny\StdLib\StdObject\UrlObject\UrlObject;

/**
 * Class for building HTTP requests.
 *
 * @package         WebinyFramework
 * @category        Architecture
 * @subcategory        Environment
 */
class Redirect
{

	/**
	 * @param UrlObject|StringObject|string $url Destination where you want to redirect.
	 * @param null                          $header
	 */
	static function redirect($url, $header=null){

		if(StdObjectWrapper::isUrlObject($url)){
			$url->goToUrl($header);
		}else if(StdObjectWrapper::isStringObject($url)){
			$url = $url->val();
		}

		$urlObject = new UrlObject($url);
		$urlObject->goToUrl($header);
	}
}