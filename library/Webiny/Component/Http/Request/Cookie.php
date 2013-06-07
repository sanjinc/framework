<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Http\Request;

use Webiny\Component\Config\ConfigObject;
use Webiny\Component\Http\Request\Cookie\CookieException;
use Webiny\Component\Http\Request\Cookie\CookieStorageInterface;
use Webiny\StdLib\Exception\Exception;
use Webiny\StdLib\FactoryLoaderTrait;
use Webiny\StdLib\StdLibTrait;

/**
 * Cookie Http component.
 *
 * @package         Webiny\Component\Http
 */

class Cookie
{
	use StdLibTrait, FactoryLoaderTrait;

	static private $_nativeDriver = '\Webiny\Component\Http\Request\Cookie\Storage\Native';

	private $_cookieBag;
	private $_storage;
	private $_cookiePrefix = '';
	private $_defaultTtl = 86400;

	/**
	 * Constructor.
	 *
	 * @param ConfigObject $config Cookie config.
	 *
	 * @throws \Webiny\Component\Http\Request\Cookie\CookieException
	 */
	function __construct(ConfigObject $config) {
		try{
			// create storage
			$this->_getStorage($config);

			// get all cookies from the driver
			$cookies = $this->_getStorage()->getAll();
			$this->_cookieBag = $this->arr($cookies);

			// set cookie prefix
			$this->_cookiePrefix = isset($config->prefix) ? $config->prefix : '';

			// set default ttl
			$this->_defaultTtl = isset($config->expiretime) ? $config->expiretime : 86400;
		}catch (\Exception $e){
			throw new CookieException($e->getMessage());
		}
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
	function save($name, $value, $expiration=null, $httpOnly = true, $path = '/'){

		// prepare params
		$name = $this->_cookiePrefix.$name;
		$expiration = (is_null($expiration)) ? $this->_defaultTtl : $expiration;
		$expiration+=time();

		try{
			$result = $this->_getStorage()->save($name, $value, $expiration, $httpOnly, $path);
			if($result){
				$this->_cookieBag->removeKey($name)->append($name, $value);
			}
		}catch (\Exception $e){
			throw new CookieException($e->getMessage());
		}

		return $result;
	}

	/**
	 * Get the cookie.
	 *
	 * @param string $name Cookie name.
	 *
	 * @return string|bool String if cookie is found, false if cookie is not found.
	 */
	function get($name){
		return $this->_cookieBag->key($this->_cookiePrefix.$name, '', true);
	}

	/**
	 * Remove the given cookie.
	 *
	 * @param string $name Cookie name.
	 *
	 * @return bool True if cookie was deleted, otherwise false.
	 * @throws \Webiny\Component\Http\Request\Cookie\CookieException
	 */
	function delete($name){
		try{
			$result = $this->_getStorage()->delete($this->_cookiePrefix.$name);
			$this->_cookieBag->removeKey($this->_cookiePrefix.$name);
		}catch (\Exception $e){
			throw new CookieException($e->getMessage());
		}

		return $result;
	}

	/**
	 * Get cookie storage driver.
	 *
	 * @param ConfigObject|null $config Cookie config - needed only if storage driver does not yet exist.
	 *
	 * @return CookieStorageInterface
	 * @throws \Webiny\Component\Http\Request\Cookie\CookieException
	 */
	private function _getStorage($config = null) {
		if(!isset($this->_storage)) {
			try {
				$driver = isset($config->storage->driver) ? $config->storage->driver : self::$_nativeDriver;
				$this->_storage = $this->factory($driver,
												 '\Webiny\Component\Http\Request\Cookie\CookieStorageInterface',
												 [$config]
				);
			} catch (Exception $e) {
				throw new CookieException($e->getMessage());
			}
		}

		return $this->_storage;
	}

}