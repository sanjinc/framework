<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny;

/**
 * Trait that enables easier access to WebinyFrameworkBase.
 * Just use this trait inside your desired class.
 *
 * Example:
 * class MyClass{
 * 		use \Webiny\WebinyTrait;
 *
 * 		public function myMethod(){
 * 			$appPath = $this->webiny()->getAppPath();
 * 		}
 * }
 *
 * @package         Webiny
 */

trait WebinyTrait
{

	/**
	 * @return WebinyFrameworkBase
	 */
	static protected function webiny() {
		return WebinyFrameworkBase::getInstance();
	}

}