<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Security\Authentication;

/**
 * Interface for authentication providers.
 *
 * @package		 Webiny\Component\Security\Authentication
 */
 
interface AuthenticationInterface{

	/**
	 * Must return the Login object.
	 * @return mixed
	 */
	function getLoginCredentials();

}