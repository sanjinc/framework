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
 * Description
 *
 * @package		 Webiny\
 */
 
class User{

	/**
	 * @var UserAbstract
	 */
	private $_user = false;

	function __construct($userProvider){
		try {
			$this->_user = $this->factory($userProvider,
															 '\Webiny\Component\Security\User\UserProviderInterface');
		} catch (\Exception $e) {
			throw new UserException($e->getMessage());
		}
	}

	function getUser(){
		return $this->_user;
	}

	function auth($username, $password){
		return $this->_user->authenticate($username, $password);
	}

	function isAuth(){
		return $this->_user->isAuthenticated();
	}
}