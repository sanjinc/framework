<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Security\Token;

use Webiny\Component\Security\User\UserAbstract;

/**
 * Token storage interface.
 *
 * @package         Webiny\Component\Security\User\TokenStorage
 */

interface TokenStorageInterface
{

	/**
	 * This function provides the token name to the storage.
	 *
	 * @param string $tokenName Token name.
	 */
	function setTokenName($tokenName);

	/**
	 * Save user authentication token.
	 *
	 * @param UserAbstract $user             Instance of UserAbstract class that holds the pre-filled object from user provider.
	 *
	 * @return bool
	 */
	function saveUserToken(UserAbstract $user);

	/**
	 * Check if auth token is present, if true, try to load the right user and return it's username.
	 *
	 * @return bool|UserAbstract False it user token is not available, otherwise the UserAbstract object is returned.
	 */
	function loadUserFromToken();

	/**
	 * Deletes the current auth token.
	 *
	 * @return bool
	 */
	function deleteUserToken();
}