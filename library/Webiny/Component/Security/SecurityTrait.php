<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Security;

/**
 * SecurityTrait provides you a simplified access to security context.
 *
 * @package		 Webiny\Component\Security
 */
 
trait SecurityTrait{

	/**
	 * Returns the instance of current user, if available.
	 * If user doesn't exist, false is returned.
	 *
	 * @return bool|User\UserAbstract
	 */
	function getUser(){
		return Security::getInstance()->getUser();
	}

	/**
	 * Checks if current user is granted with the given role.
	 *
	 * @param string $role Role name.
	 *
	 * @return bool True if user is granted with the role, otherwise false is returned.
	 */
	function isGranted($role){
		return Security::getInstance()->getUser()->hasRole($role);
	}
}