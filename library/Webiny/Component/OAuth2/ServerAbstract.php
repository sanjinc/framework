<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\OAuth2;


/**
 * OAuth2 server abstract class.
 *
 * This class must be extended by every OAuth2 server class.
 * The class provides universal methods for communicating with selected OAuth2 server.
 *
 * @package         Webiny\Component\OAuth2
 */

abstract class ServerAbstract
{

	/**
	 * @var OAuth2
	 */
	private $_oauth2;

	/**
	 * Returns an array [url, params].
	 * 'url' - holds the destination url for accessing user details on the OAuth2 server.
	 * 'params' - an optional array of additional parameters that would be sent together with the request.
	 *
	 * @return array
	 */
	abstract protected function _getUserDetailsTargetData();

	/**
	 * This method is called automatically when the OAuth2 server returns a response containing user details.
	 * The method should process the response an return and instance of OAuth2User.
	 *
	 * @param array $result OAuth2 server response.
	 *
	 * @return OAuth2User
	 * @throws \OAuth2\Exception
	 */
	abstract protected function _processUserDetails($result);

	/**
	 * Base constructor.
	 *
	 * @param OAuth2 $oauth2
	 */
	function __construct(OAuth2 $oauth2) {
		$this->_oauth2 = $oauth2;
	}

	/**
	 * Tries to get user details for the current OAuth2 server.
	 * If you wish to get full account details you must use the rawRequest method because this one returns only the
	 * standardized response in a form of OAuth2User object.
	 *
	 * @return OAuth2User
	 */
	function getUserDetails() {
		$requestData = $this->_getUserDetailsTargetData();

		$result = $this->rawRequest($requestData['url'], $requestData['params']);

		return $this->_processUserDetails($result);
	}

	/**
	 * Preforms an raw request on the current OAuth2 server.
	 *
	 * @param string $url        Targeted url.
	 * @param array  $parameters Query params that will be send along the request.
	 *
	 * @return array
	 */
	private function rawRequest($url, $parameters = []) {
		$request = new OAuth2Request($this->_oauth2);

		$request->setUrl($url);
		$request->params($parameters);

		return $request->executeRequest();
	}
}