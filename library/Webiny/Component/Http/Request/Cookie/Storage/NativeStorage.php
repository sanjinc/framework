<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Http\Request\Cookie\Storage;

use Webiny\Component\Config\ConfigObject;
use Webiny\Component\Http\Request\Cookie\CookieException;
use Webiny\Component\Http\Request\Cookie\CookieStorageInterface;
use Webiny\Component\Http\HttpTrait;
use Webiny\StdLib\StdLibTrait;

/**
 * Native cookie storage.
 *
 * @package         Webiny\Component\Http\Request\Cookie\Storage
 */

class NativeStorage implements CookieStorageInterface
{
	use HttpTrait;

	private $_domain = '';
	private $_https = false;

	/**
	 * Constructor.
	 *
	 * @param ConfigObject $config Cookie config.
	 */
	function __construct(ConfigObject $config) {
		$this->_domain = $this->request()->getHostName();
		$this->_https = $this->request()->isRequestSecured();
	}

	/**
	 * Save a cookie.
	 *
	 * @param string $name       Name of the cookie.
	 * @param string $value      Cookie value.
	 * @param int    $expiration Timestamp when the cookie should expire.
	 * @param bool   $httpOnly  Is the cookie https-only or not.
	 * @param string $path       Path under which the cookie is accessible.
	 *
	 * @return bool True if cookie was save successfully, otherwise false.
	 * @throws CookieException
	 */
	function save($name, $value, $expiration, $httpOnly = true, $path = '/') {
		try{
			return setcookie($name, $value, $expiration, $path, $this->_domain, $this->_https, $httpOnly);
		}catch (\ErrorException $e){
			throw new CookieException($e->getMessage());
		}
	}

	/**
	 * Get all stored cookies.
	 *
	 * @return array A list of all stored cookies.
	 */
	function getAll() {
		return $_COOKIE;
	}

	/**
	 * Delete the given cookie.
	 *
	 * @param string $name Name of the cookie.
	 *
	 * @return bool True if cookie was deleted, otherwise false.
	 */
	function delete($name) {
		return setcookie($name, '', (time() - 86400), '/', $this->_domain, $this->_https, true);
	}
}