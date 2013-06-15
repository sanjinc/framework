<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Security\Authentication\Providers;

/**
 * Login object is a wrapper that holds the username and password submitted by current request.
 *
 * @package		 Webiny\Component\Security\Authentication
 */
 
class Login{

	private $_username = '';
	private $_password = '';
	private $_rememberMe = false;

	function __construct($username, $password, $rememberMe = false){
		$this->_username = $username;
		$this->_password = $password;
		$this->_rememberMe  =$rememberMe;
	}

	function getUsername(){
		return $this->_username;
	}

	function getPassword(){
		return $this->_password;
	}

	function getRememberMe(){
		return $this->_rememberMe;
	}
}