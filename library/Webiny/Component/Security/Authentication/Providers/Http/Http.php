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
use Webiny\Component\Security\Token\Token;
use Webiny\Component\Security\User\User;
use Webiny\Component\Security\User\UserAbstract;

/**
 * Http authentication
 *
 * @package         Webiny\Component\Security\Authentication\Http
 */

class Http implements AuthenticationInterface
{

	use HttpTrait;

	const USERNAME = 'PHP_AUTH_USER';
	const PASSWORD = 'PHP_AUTH_PW';
	const DIGEST = 'PHP_AUTH_DIGEST';

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

		$username = $this->request()->session()->get('username', '');
		$password = $this->request()->session()->get('password', '');

		return new Login($username, $password, false);
	}

	/**
	 * This method is triggered when the user opens the login page.
	 * On this page you must ask the user to provide you his credentials which should then be passed to the login submit page.
	 * @param ConfigObject $config Firewall config
	 *
	 * @return mixed
	 */
	function triggerLogin($config) {
		$headers = [
			'WWW-Authenticate: Basic realm="' . $config->realm_name .'"',
			'HTTP/1.0 401 Unauthorized'
		];

		foreach ($headers as $h) {
			header($h);
		}

		if($this->request()->session()->get('login_retry')=='true'){
			$this->request()->session()->delete('login_retry');
			die('Your are not authenticated.');
		}

		// once we get the username and password, we store them into the session and redirect to login submit path
		if($this->request()->server()->get(self::USERNAME, '')!='' && $this->request()->session()->get('logout', 'false')!='true') { // php Basic HTTP auth
			$username = $this->request()->server()->get(self::USERNAME);
			$password = $this->request()->server()->get(self::PASSWORD);
		} else {
			$this->request()->session()->delete('logout');
			die('Your are not authenticated.');
		}

		$this->request()->session()->save('username', $username);
		$this->request()->session()->save('password', $password);

		$this->request()->redirect($config->login->submit_path);
	}

	/**
	 * This callback is triggered after we validate the given login data, and the data is not valid.
	 * Use this callback to clear the submit data from the previous request so that you don't get stuck in an
	 * infinitive loop between login and login submit page.
	 */
	function invalidLoginProvidedCallback() {
		$this->request()->session()->delete('username');
		$this->request()->session()->delete('password');
		$this->request()->session()->save('login_retry', 'true');
	}

	/**
	 * This callback is triggered after we have validated user credentials.
	 *
	 * @param UserAbstract $user
	 */
	function loginSuccessfulCallback(UserAbstract $user) {
		// nothing to do
	}

	/**
	 * This callback is triggered when the system has managed to retrieve the user from the stored token (either session)
	 * or cookie.
	 *
	 * @param UserAbstract $user
	 * @param Token        $token
	 *
	 * @return mixed
	 */
	function userAuthorizedByTokenCallback(UserAbstract $user, Token $token) {
		// nothing to do
	}

	function logoutCallback() {
		$this->invalidLoginProvidedCallback();
		$this->request()->session()->delete('login_retry');
		$this->request()->session()->save('logout', 'true');
	}
}