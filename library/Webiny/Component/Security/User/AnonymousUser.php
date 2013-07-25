<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Security\User;

/**
 * Anonymous user class.
 * This is the user class that is created if we cannot identify the user.
 *
 * @package		 Webiny\Component\Security\User
 */
 
class AnonymousUser extends UserAbstract{

	/**
	 * Base constructor.
	 */
	function __construct(){
		parent::populate('anonymous', '', [], false);
	}

}