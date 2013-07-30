<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Security;

use Webiny\Component\EventManager\Event;
use Webiny\Component\Security\User\UserAbstract;

/**
 * This class is passed along with the events fired by Security component.
 *
 * @package		 Webiny\Component\Security
 */
 
class SecurityEvent extends Event{

	private $_user;

	/**
	 * Base constructor.
	 *
	 * @param UserAbstract $user
	 */
	function __construct(UserAbstract $user)
	{
		$this->_user = $user;
	}

	/**
	 * Returns the instance of current user.
	 *
	 * @return UserAbstract
	 */
	function getUser(){
		return $this->_user;
	}
}