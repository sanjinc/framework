<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Bridge\OAuth2\OAuth2;

use OAuth2\Client;
use Webiny\Bridge\OAuth2\OAuth2Abstract;
use Webiny\Bridge\OAuth2\OAuth2Exception;
use Webiny\Component\Http\HttpTrait;
use Webiny\StdLib\StdLibTrait;

/**
 * Bridge for OAuth2 library by Charron Pierrick (https://github.com/adoy/PHP-OAuth2)
 *
 * @package         Webiny\Bridge\OAuth2\OAuth2
 */

class OAuth2 extends OAuth2Abstract
{

	use HttpTrait, StdLibTrait;

	private $_instance = null;
	private $_pathToCertificate = '';
	private $_accessToken = '';

	/**
	 * Base constructor.
	 *
	 * @param string $clientId     Client id.
	 * @param string $clientSecret Client secret.
	 * @param string $redirectUri  Target url where to redirect after authentication.
	 */
	function __construct($clientId, $clientSecret, $redirectUri) {
		$this->_clientId = $clientId;
		$this->_clientSecret = $clientSecret;
		$this->_redirectUri = $redirectUri;

		$this->_instance = new Client($clientId, $clientSecret, Client::AUTH_TYPE_URI, null);
	}

	/**
	 * Requests the access token from the OAuth server.
	 * You can call this method only on the OAuth redirect_uri page or else the request will fail.
	 *
	 * @throws \Webiny\Bridge\OAuth2\OAuth2Exception
	 * @return string Access token.
	 */
	function requestAccessToken() {

		$oauthServer = $this->_getOAuth2Server();

		$params = [
			'code'         => $this->request()->query('code', ''),
			'redirect_uri' => $this->getRedirectURI()
		];

		$response = $this->_instance->getAccessToken($oauthServer['token_url'], 'authorization_code', $params);

		if(!$this->isArray($response)){
			throw new OAuth2Exception('Invalid response while trying to get the access token.');
		}

		if(isset($response['result']['error'])){
			throw new OAuth2Exception($response['result']['error']['message']);
		}

		parse_str($response['result'], $info);
		$this->_instance->setAccessToken($info['access_token']);
		$this->_accessToken = $info['access_token'];

		return $info['access_token'];
	}

	/**
	 * Get access  token.
	 *
	 * @throws \Webiny\Bridge\OAuth2\OAuth2Exception
	 * @return string Access token.
	 */
	function getAccessToken(){
		if($this->_accessToken==''){
			throw new OAuth2Exception('Before you can get the access token, you first must request it from the OAuth2 server.');
		}

		return $this->_accessToken;
	}

	/**
	 * Set the access token.
	 *
	 * @param string $accessToken Access token.
	 *
	 * @return void
	 */
	function setAccessToken($accessToken) {
		$this->_instance->setAccessToken($accessToken);
		$this->_accessToken = $accessToken;
	}

	/**
	 * Set the certificate used by OAuth2 requests.
	 *
	 * @param string $pathToCertificate Absolute path to the certificate file.
	 *
	 * @return void
	 */
	function setCertificate($pathToCertificate) {
		$this->_pathToCertificate = $pathToCertificate;
		$this->_instance = new Client($this->_clientId, $this->_clientSecret, Client::AUTH_TYPE_URI, $pathToCertificate);
	}

	/**
	 * Returns the path to certificate.
	 *
	 * @return string Path to certificate.
	 */
	function getCertificate() {
		return $this->_pathToCertificate;
	}
}