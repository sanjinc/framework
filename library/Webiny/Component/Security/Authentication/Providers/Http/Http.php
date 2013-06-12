<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Security\Authentication\Providers\Http;

use Webiny\Component\Http\HttpTrait;
use Webiny\Component\Security\Authentication\Providers\AuthenticationInterface;
use Webiny\Component\Security\Authentication\Providers\Login;

/**
 * Http authentication
 *
 * @package		 Webiny\Component\Security\Authentication\Http
 */
 
class Http implements AuthenticationInterface {

	use HttpTrait;

	const USERNAME = 'PHP_AUTH_USER';
	const PASSWORD = 'PHP_AUTH_PW';

	/**
	 * This method is triggered on the login submit page where user credentials are submitted.
	 * On this page the provider should create a new Login object from those credentials, and return the object.
	 * This object will be then validated my user providers.
	 *
	 * @param ConfigObject $config Firewall config
	 *
	 * @return Login
	 */
	function getLoginObject($config) {

		$username = $this->request()->session()->get('username');
		$password = $this->request()->session()->get('password');

		return new Login($username, $password, false);
	}

	/**
	 * @param int $status There are two statuses available:
	 * 							1 = first time entered the login page
	 * 							2 = login was submitted but the credentials did not match
	 * @param ConfigObject $config Firewall config
	 *
	 * @return mixed
	 */
	function triggerLogin($status, $config) {
		$headers = [
			'WWW-Authenticate: Digest realm="'.$config->realm_name.
			'",qop="auth",nonce="'.uniqid().'",opaque="'.md5($config->realm_name).'"',
			'HTTP/1.0 401 Unauthorized'
		];
		foreach($headers as $h){
			header($h);
		}

		// once we get the username and password, we store them into the session and redirect to login submit path
		if($this->request()->server()->get(self::USERNAME)){


			$this->request()->session()->save('username', $this->request()->server()->get(self::USERNAME));
			$this->request()->session()->save('password', $this->request()->server()->get(self::PASSWORD));

			$this->request()->redirect($config->login->submit_path);
		}

		die('Your are not authenticated.');
	}
}