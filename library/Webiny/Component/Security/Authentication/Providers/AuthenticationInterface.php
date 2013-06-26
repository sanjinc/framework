<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Security\Authentication\Providers;

use Webiny\Component\Config\ConfigObject;

/**
 * Interface for authentication providers.
 *
 * @package         Webiny\Component\Security\Authentication
 */

interface AuthenticationInterface
{

	/**
	 * This method is triggered on the login submit page where user credentials are submitted.
	 * On this page the provider should create a new Login object from those credentials, and return the object.
	 * This object will be then validated my user providers.
	 *
	 * @param ConfigObject $config Firewall config
	 *
	 * @return Login
	 */
	function getLoginObject($config);

	/**
	 * @param int $status There are two statuses available:
	 * 							1 = first time entered the login page
	 * 							2 = login was submitted but the credentials did not match
	 * @param ConfigObject $config Firewall config
	 *
	 * @return mixed
	 */
	function triggerLogin($status, $config);
}