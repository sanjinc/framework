<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\OAuth2;

use Webiny\Bridge\OAuth2\OAuth2Abstract;
use Webiny\WebinyTrait;

/**
 * OAuth2 component.
 *
 * This component is used for communication with OAuth2 servers like Facebook Graph API.
 *
 * @package		 Webiny\Component\OAuth2
 */
 
class OAuth2{

	use WebinyTrait;

	/**
	 * @var OAuth2Abstract
	 */
	private $_instance;
	/**
	 * @var ServerAbstract
	 */
	private $_server;

	/**
	 * Base constructor.
	 * NOTE: Use OAuth2Loader::getInstance method to get an OAuth2 instance.
	 *
	 * @param OAuth2Abstract $instance
	 */
	function __construct(OAuth2Abstract $instance){
		$this->_instance = $instance;

		$server = $this->_getServerClass($instance->getServerName());
		$this->_server = new $server($this);
	}

	/**
	 * Method that reads OAuth2 server configuration.
	 *
	 * @param string $serverName Server name, example 'facebook'
	 *
	 * @return string
	 * @throws OAuth2Exception
	 */
	private function _getServerClass($serverName){
		$oauthServers = $this->webiny()->getConfig()->get('components.oauth2.servers', false);
		if(!$oauthServers){
			throw new OAuth2Exception('Unable to read config: components.oauth2.servers');
		}

		if(!isset($oauthServers->toArray()[$serverName])){
			throw new OAuth2Exception('OAuth2 server "'.$serverName.'" not found in components.oauth2.servers.');
		}

		return $oauthServers->toArray()[$serverName];
	}

	/**
	 * @return ServerAbstract
	 */
	function request(){
		return $this->_server;
	}

	/**
	 * Get client id.
	 *
	 * @return string Client id.
	 */
	function getClientId() {
		return $this->_instance->getClientId();
	}

	/**
	 * Get client secret.
	 *
	 * @return string Client secret.
	 */
	function getClientSecret() {
		return $this->_instance->getClientSecret();
	}

	/**
	 * Requests the access token from the OAuth server.
	 * You can call this method only on the OAuth redirect_uri page or else the request will fail.
	 *
	 * @throws \Webiny\Bridge\OAuth2\OAuth2Exception
	 * @return string Access token.
	 */
	function requestAccessToken() {
		$tokenUrl = $this->_processUrl($this->_server->getAccessTokenUrl());
		$response = $this->_instance->requestAccessToken($tokenUrl);
		$accessToken = $this->_server->processAuthResponse($response);
		$this->_instance->setAccessToken($accessToken);

		return $accessToken;
	}

	/**
	 * Get access  token.
	 *
	 * @return string Access token.
	 */
	function getAccessToken() {
		return $this->_instance->getAccessToken();
	}

	/**
	 * Get the defined redirect URI.
	 *
	 * @return string Redirect URI.
	 */
	function getRedirectURI() {
		return $this->_instance->getRedirectURI();
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
	}

	/**
	 * Set the certificate used by OAuth2 requests.
	 *
	 * @param string $pathToCertificate Absolute path to the certificate file.
	 *
	 * @return void
	 */
	function setCertificate($pathToCertificate) {
		$this->_instance->setCertificate($pathToCertificate);
	}

	/**
	 * Returns the path to certificate.
	 *
	 * @return string Path to certificate.
	 */
	function getCertificate() {
		return $this->_instance->getCertificate();
	}

	/**
	 * Set the request scope.
	 *
	 * @param string $scope A comma-separated list of parameters. Example: email,extender_permissions
	 *
	 * @return void
	 */
	function setScope($scope) {
		$this->_instance->setScope($scope);
	}

	/**
	 * Get the defined scope.
	 *
	 * @return string A comma separated list of parameters.
	 */
	function getScope() {
		return $this->_instance->getScope();
	}

	/**
	 * Set the state parameter.
	 *
	 * @param string $state State name.
	 *
	 * @return void.
	 */
	function setState($state) {
		$this->_instance->setState($state);
	}

	/**
	 * Get the state parameter.
	 *
	 * @return string State parameter
	 */
	function getState() {
		return $this->_instance->getState();
	}

	/**
	 * Returns the name of access token param. Its usually either 'access_token' or 'token' based on the OAuth2 server.
	 *
	 * @return string
	 */
	function getAccessTokenName() {
		return $this->_instance->getAccessTokenName();
	}

	/**
	 * Returns the authentication url.
	 *
	 * @return string Authentication url
	 */
	function getAuthenticationUrl() {
		return $this->_processUrl($this->_server->getAuthorizeUrl());
	}

	private function _processUrl($url){
		$vars = [
			'{CLIENT_ID}'    => $this->getClientId(),
			'{REDIRECT_URI}' => $this->getRedirectURI(),
			'{SCOPE}'        => $this->getScope(),
			'{STATE}'        => $this->getState(),
			" "				 => '',
			"\n"			 => '',
			"\r"			 => '',
			"\t"			 => ''
		];

		$url = str_replace(array_keys($vars), array_values($vars), $url);

		return $url;
	}
}