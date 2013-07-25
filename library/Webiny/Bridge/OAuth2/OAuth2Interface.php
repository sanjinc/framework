<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Bridge\OAuth2;

/**
 * OAuth2 bridge interface.
 * All of OAuth2 bridges must implement this interface in order to be compliant with WebinyFramework.
 *
 * @package         Webiny\Bridge\OAuth2
 */

interface OAuth2Interface
{

	/**
	 * Base constructor.
	 *
	 * @param string $clientId Client id.
	 * @param string $clientSecret Client secret.
	 * @param string $redirectUri Target url where to redirect after authentication.
	 */
	function __construct($clientId, $clientSecret, $redirectUri);

	/**
	 * Get client id.
	 *
	 * @return string Client id.
	 */
	function getClientId();

	/**
	 * Get client secret.
	 *
	 * @return string Client secret.
	 */
	function getClientSecret();

	/**
	 * Requests the access token from the OAuth server.
	 * You can call this method only on the OAuth redirect_uri page or else the request will fail.
	 *
	 * @throws \Webiny\Bridge\OAuth2\OAuth2Exception
	 * @return string Access token.
	 */
	function requestAccessToken();

	/**
	 * Get access  token.
	 *
	 * @return string Access token.
	 */
	function getAccessToken();


	/**
	 * Get the defined redirect URI.
	 *
	 * @return string Redirect URI.
	 */
	function getRedirectURI();

	/**
	 * Set the access token.
	 *
	 * @param string $accessToken Access token.
	 *
	 * @return void
	 */
	function setAccessToken($accessToken);

	/**
	 * Set the certificate used by OAuth2 requests.
	 *
	 * @param string $pathToCertificate Absolute path to the certificate file.
	 *
	 * @return void
	 */
	function setCertificate($pathToCertificate);

	/**
	 * Returns the path to certificate.
	 *
	 * @return string Path to certificate.
	 */
	function getCertificate();

	/**
	 * Set the request scope.
	 *
	 * @param string $scope A comma-separated list of parameters. Example: email,extender_permissions
	 *
	 * @return void
	 */
	function setScope($scope);

	/**
	 * Get the defined scope.
	 *
	 * @return string A comma separated list of parameters.
	 */
	function getScope();

	/**
	 * Set the state parameter.
	 *
	 * @param string $state State name.
	 *
	 * @return void.
	 */
	function setState($state);

	/**
	 * Get the state parameter.
	 *
	 * @return string State parameter
	 */
	function getState();

	/**
	 * Returns the name of access token param. Its usually either 'access_token' or 'token' based on the OAuth2 server.
	 *
	 * @return string
	 */
	function getAccessTokenName();

	/**
	 * Returns the authentication url.
	 *
	 * @return string Authentication url
	 */
	function getAuthenticationUrl();
}