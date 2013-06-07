<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Security\Authorization;

/**
 * Description
 *
 * @package		 Webiny\Component\Security\Authorization
 */
 
class AccessControl{

	function __construct($accessControl){
		// setup role hierarchy

		// setup access control paths and rules
	}

	function needsToAuthorize(){
		// true or false
	}

	function canAccess($userProvider){
		// true or false
	}
}