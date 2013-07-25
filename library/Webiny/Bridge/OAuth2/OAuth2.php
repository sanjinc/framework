<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Bridge\OAuth2;

use Webiny\StdLib\Exception\Exception;
use Webiny\StdLib\StdLibTrait;
use Webiny\WebinyTrait;

/**
 * OAuth2 bridge to external OAuth2 libraries.
 *
 * @package         Webiny\Bridge\OAuth2
 */

class OAuth2
{
	use WebinyTrait, StdLibTrait;

	/**
	 * Path to the default OAuth2 bridge library.
	 *
	 * @var string
	 */
	static private $_library = '\Webiny\Bridge\OAuth2\OAuth2\OAuth2';

	/**
	 * Get the name of bridge library which will be used as the driver.
	 *
	 * @return string
	 */
	static function _getLibrary() {
		if(isset(self::webiny()->getConfig()->bridges->oauth2)) {
			return self::webiny()->getConfig()->bridges->oauth2;
		}

		return self::$_library;
	}

	/**
	 * Change the default library used for the driver.
	 *
	 * @param string $pathToClass Path to the new bridge class.
	 *                            The class must implement \Webiny\Bridge\OAuth2\OAuth2Interface.
	 */
	static function setLibrary($pathToClass) {
		self::$_library = $pathToClass;
	}

	/**
	 * Create an instance of an OAuth2 driver.
	 *
	 * @param string $clientId     Client id.
	 * @param string $clientSecret Client secret.
	 * @param string $redirectUri  Target url where to redirect after authentication.
	 * @param string $certificateFile
	 *
	 * @throws Exception
	 * @return OAuth2Abstract
	 */
	static function getInstance($clientId, $clientSecret, $redirectUri, $certificateFile = '') {
		$driver = static::_getLibrary();

		try {
			$instance = new $driver($clientId, $clientSecret, $redirectUri);
		} catch (\Exception $e) {
			throw new Exception('Unable to create an instance of '.$driver);
		}

		if(!self::isInstanceOf($instance, '\Webiny\Bridge\OAuth2\OAuth2Interface')) {
			throw new Exception(Exception::MSG_INVALID_ARG, [
															'driver',
															'\Webiny\Bridge\OAuth2\OAuth2Interface'
															]);
		}

		return $instance;
	}
}