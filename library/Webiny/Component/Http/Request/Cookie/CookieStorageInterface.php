<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Http\Request\Cookie;

use Webiny\Component\Config\Config;
use Webiny\Component\Config\ConfigObject;

/**
 * Cookie storage interface.
 * If you wish to create your own cookie storage, you must implement this interface.
 *
 * @package         Webiny\Component\Http\Request\Cookie
 */

interface CookieStorageInterface
{

	/**
	 * Constructor.
	 *
	 * @param ConfigObject $config Cookie config.
	 */
	function __construct(ConfigObject $config);

	/**
	 * Save a cookie.
	 *
	 * @param string $name       Name of the cookie.
	 * @param string $value      Cookie value.
	 * @param int    $expiration Timestamp when the cookie should expire.
	 * @param bool   $httpOnly   Is the cookie https-only or not.
	 * @param string $path       Path under which the cookie is accessible.
	 *
	 * @return bool True if cookie was save successfully, otherwise false.
	 * @throws CookieException
	 */
	function save($name, $value, $expiration, $httpOnly = true, $path = '/');

	/**
	 * Get all stored cookies.
	 *
	 * @return array A list of all stored cookies.
	 */
	function getAll();

	/**
	 * Delete the given cookie.
	 *
	 * @param string $name Name of the cookie.
	 *
	 * @return bool True if cookie was deleted, otherwise false.
	 */
	function delete($name);

}