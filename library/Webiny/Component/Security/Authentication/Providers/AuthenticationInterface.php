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
use Webiny\Component\Security\Token\Token;
use Webiny\Component\Security\User\UserAbstract;

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
	 * This object will be then validated by user providers.
	 *
	 * @param ConfigObject $config Firewall config
	 *
	 * @return Login
	 */
	function getLoginObject($config);

	/**
	 * This method is triggered when the user openes the login page.
	 * On this page you must ask the user to provide you his credentials which should then be passed to the login submit page.
	 *
	 * @param ConfigObject $config Firewall config
	 *
	 * @return mixed
	 */
	function triggerLogin($config);

	/**
	 * This callback is triggered after we validate the given login data from getLoginObject, and the data IS NOT valid.
	 * Use this callback to clear the submit data from the previous request so that you don't get stuck in an
	 * infinitive loop between login page and login submit page.
	 */
	function invalidLoginProvidedCallback();

	/**
	 * This callback is triggered after we have validated user credentials and have created a user auth token.
	 *
	 * @param UserAbstract $user
	 */
	function loginSuccessfulCallback(UserAbstract $user);

	/**
	 * This callback is triggered when the system has managed to retrieve the user from the storred token (either session)
	 * or cookie.
	 *
	 * @param UserAbstract $user
	 * @param Token $token
	 *
	 * @return mixed
	 */
	function userAuthorizedByTokenCallback(UserAbstract $user, Token $token);
}