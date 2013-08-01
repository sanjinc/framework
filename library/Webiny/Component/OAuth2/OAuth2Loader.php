<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\OAuth2;

use Webiny\Component\Http\HttpTrait;
use Webiny\StdLib\StdLibTrait;
use Webiny\WebinyTrait;

/**
 * OAuth2 loader.
 * Use this class to get an instance of OAuth2 component.
 *
 * @package		 Webiny\Component\OAuth2
 */
 
class OAuth2Loader{

	use WebinyTrait, StdLibTrait, HttpTrait;

	static private $_instances = [];

	/**
	 * Returns an instance to OAuth2 server based on the current configuration.
	 *
	 * @param string $key Unique identifier for the OAuth2 server that you wish to get.
	 *
	 * @return array|OAuth2
	 * @throws OAuth2Exception
	 */
	static function getInstance($key){

		if(isset(self::$_instances[$key])){
			return self::$_instances;
		}

		$oauth2Config = self::webiny()->getConfig()->get('oauth2.'.$key);

		if(self::isNull($oauth2Config)){
			throw new OAuth2Exception('Unable to read "oauth2.'.$key.'" configuration.');
		}

		$redirectUri = self::request()->getCurrentUrl(true)->setPath($oauth2Config->redirect_uri)->setQuery('')->val();

		$instance = \Webiny\Bridge\OAuth2\OAuth2::getInstance(
			$oauth2Config->client_id,
			$oauth2Config->client_secret,
			$redirectUri);

		$instance->setOAuth2Server($oauth2Config->server);
		$instance->setScope($oauth2Config->get('scope', ''));

		return new OAuth2($instance);
	}

}