<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Security\User\Providers\Memory;

use Webiny\Component\Security\User\Exceptions\UserNotFoundException;
use Webiny\Component\Security\User\Providers\Memory\MemoryException;
use Webiny\Component\Security\User\Providers\Memory\User;
use Webiny\Component\Security\User\UserAbstract;
use Webiny\Component\Security\User\UserProviderInterface;
use Webiny\StdLib\StdLibTrait;

/**
 * Description
 *
 * @package        Webiny\Component\Security\User\Providers
 */

class MemoryProvider implements UserProviderInterface
{
	use StdLibTrait;

	private $_users = [];

	/**
	 * Constructor.
	 */
	function __construct() {
		$args = func_get_args();
		$this->_addUsers($args[0]);
	}

	/**
	 * Check user data and, if valid, store them.
	 *
	 * @param array $users List of user accounts.
	 *
	 * @return bool
	 * @throws MemoryException
	 */
	private function _addUsers($users) {
		if(!is_array($users)) {
			return false;
		}

		foreach ($users as $username => $data) {
			if($username=='' || !$this->isString($username)) {
				throw new MemoryException('Cannot store a user that doesn\'t have a username.');
			}

			if(!isset($data['password'])){
				$data['password'] = '';
			}

			if(!isset($data['roles']) || !$this->isArray($data['roles'])){
				$data['roles'] = [];
			}

			$this->_users[$username] = $data;
		}
	}

	/**
	 * Get the user from user provided for the given $username.
	 *
	 * @param string $username Username
	 *
	 * @return UserAbstract
	 *
	 * @throws UserNotFoundException
	 */
	function getUserByUsername($username) {
		if(!isset($this->_users[$username]) || !$this->isArray($this->_users[$username])){
			throw new UserNotFoundException('User "'.$username.'" was not found.');
		}

		$userData = $this->_users[$username];

		$user = new User();
		$user->populate($username, $userData['password'], $userData['roles'], false);

		return $user;
	}
}