<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Registry;

/**
 * Trait for Registry component.
 *
 * @package		 Webiny\Component\Registry
 */
 
trait RegistryTrait{

	/**
	 * @return $this
	 */
	public function registry(){
		return Registry::getInstance();
	}
}