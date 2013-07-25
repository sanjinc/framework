<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Bridge\OAuth2;

use Webiny\StdLib\StdLibTrait;

/**
 * OAuth2 abstract class.
 * This class implements the OAuth2Inteface and adds some methods that ease the implementation of OAuth2 bridge libraries.
 *
 * @package         Webiny\Bridge\OAuth2
 */

abstract class OAuth2Abstract implements OAuth2Interface
{

	use StdLibTrait;

	/**
	 * Request scope, a comma-separated list of parameters.
	 *
	 * @var string
	 */
	protected $_scope = '';

	/**
	 * Request state.
	 *
	 * @var string
	 */
	protected $_state = '';

	/**
	 * OAuth2 Client ID.
	 *
	 * @var string
	 */
	protected $_clientId = '';

	/**
	 * Client secret for defined Client ID.
	 *
	 * @var string
	 */
	protected $_clientSecret = '';

	/**
	 * A URI where the user will be redirected after OAuth2 authorization.
	 *
	 * @var string
	 */
	protected $_redirectUri = '';


	/**
	 * Name of the OAuth2 server.
	 *
	 * @var string
	 */
	private $_serverName = '';

	/**
	 * Optional array that is provided in case of 'custom' server name.
	 *
	 * @var null
	 */
	private $_serverOptions = null;

	/**
	 * Name of the access token.
	 *
	 * @var string
	 */
	private $_accessTokenName = 'access_token';


	/**
	 * Currently supported servers are:
	 * [facebook, foursquare, github, google, instagram, linkedin, microsoft, salesforce].
	 *
	 * You can also paste 'custom' as server name, but in that case you must also provide the $options array that has
	 * auth_url and token_url as array keys.
	 * You can put variables like {CLIENT_ID}, {REDIRECT_URI}, {SCOPE} and {STATE} inside the auth_url and this function
	 * will replace them with current values.
	 *
	 * @param string     $serverName Name of the OAuth2 server for which you wish to get the auth_url and token_url.
	 * @param null|array $options    Optional array that you must provide in case of 'custom' server name.
	 */
	function setOAuth2Server($serverName, $options = null) {
		$this->_serverName = $serverName;
		$this->_serverOptions = $options;
	}

	/**
	 * Set the request scope.
	 *
	 * @param string $scope A comma-separated list of parameters. Example: email,extender_permissions
	 *
	 * @return void
	 */
	function setScope($scope) {
		$this->_scope = $scope;
	}

	/**
	 * Get the defined scope.
	 *
	 * @return string A comma separated list of parameters.
	 */
	function getScope() {
		return $this->_scope;
	}

	/**
	 * Set the state parameter.
	 *
	 * @param string $state State name.
	 *
	 * @return void.
	 */
	function setState($state) {
		$this->_state = $state;
	}

	/**
	 * Get the state parameter.
	 *
	 * @return string State parameter
	 */
	function getState() {
		return $this->_state;
	}

	/**
	 * Get client id.
	 *
	 * @return string Client id.
	 */
	function getClientId() {
		return $this->_clientId;
	}

	/**
	 * Get client secret.
	 *
	 * @return string Client secret.
	 */
	function getClientSecret() {
		return $this->_clientSecret;
	}


	/**
	 * Get the defined redirect URI.
	 *
	 * @return string Redirect URI.
	 */
	function getRedirectURI() {
		return $this->str($this->_redirectUri)->urlDecode()->val();
	}

	/**
	 * Returns the auth_url and token_url based on server name.
	 *
	 * @return array Array [auth_url, token_url]
	 *
	 * @throws OAuth2Exception
	 */
	protected function _getOAuth2Server() {
		$server = [
			'auth_url'  => '',
			'token_url' => ''
		];

		if($this->_serverName == '') {
			throw new OAuth2Exception('You didn\'t set the OAuth2 server name.
										Please set it by calling the setOAuth2Server method.');
		}

		switch ($this->_serverName) {

			case 'custom':
				if(!isset($options['auth_url']) ||
					!isset($options['token_url']) ||
					$options['auth_url'] == '' ||
					$options['token_url'] == ''
				) {
					throw new OAuth2Exception('The "custom" server must have "auth_url" and
												"token_url" defined in the $options array.');
				}

				$server = $options;
				break;

			case 'facebook':
				$server['auth_url'] = 'https://graph.facebook.com/oauth/authorize
											?response_type=code
											&client_id={CLIENT_ID}
											&redirect_uri={REDIRECT_URI}
											&scope={SCOPE}
											&state={STATE}';

				$server['token_url'] = 'https://graph.facebook.com/oauth/access_token';
				$this->_accessTokenName = 'access_token';
				break;

			case 'foursquare':
				$server['auth_url'] = 'https://foursquare.com/oauth2/authorize
											?client_id={CLIENT_ID}
											&scope={SCOPE}
											&response_type=code
											&redirect_uri={REDIRECT_URI}
											&state={STATE}';
				$server['token_url'] = 'https://foursquare.com/oauth2/access_token';
				$this->_accessTokenName = 'oauth_token';
				break;

			case 'github':
				$server['auth_url'] = 'https://github.com/login/oauth/authorize
											?client_id={CLIENT_ID}
											&redirect_uri={REDIRECT_URI}
											&scope={SCOPE}
											&state={STATE}';
				$server['token_url'] = 'https://github.com/login/oauth/access_token';
				$this->_accessTokenName = 'access_token';
				break;

			case 'google':
				$server['auth_url'] = 'https://accounts.google.com/o/oauth2/auth
											?response_type=code
											&client_id={CLIENT_ID}
											&redirect_uri={REDIRECT_URI}
											&scope={SCOPE}
											&state={STATE}';
				$server['token_url'] = 'https://accounts.google.com/o/oauth2/token';
				$this->_accessTokenName = 'access_token';
				break;

			case 'instagram':
				$server['auth_url'] = 'https://api.instagram.com/oauth/authorize/
											?client_id={CLIENT_ID}
											&redirect_uri={REDIRECT_URI}
											&scope={SCOPE}
											&response_type=code
											&state={STATE}';
				$server['token_url'] = 'https://api.instagram.com/oauth/access_token';
				$this->_accessTokenName = 'access_token';
				break;

			case 'linkedin':
				$server['auth_url'] = 'https://www.linkedin.com/uas/oauth2/authorization?response_type=code
                                           &client_id={CLIENT_ID}
                                           &scope={SCOPE}
                                           &state={STATE}
                                           &redirect_uri={REDIRECT_URI}';
				$server['token_url'] = 'https://www.linkedin.com/uas/oauth2/accessToken';
				$this->_accessTokenName = 'oauth2_access_token';
				break;

			case 'microsoft':
				$server['auth_url'] = 'https://login.live.com/oauth20_authorize.srf
											?client_id={CLIENT_ID}
											&scope={SCOPE}
											&response_type=code
											&redirect_uri={REDIRECT_URI}
											&state={STATE}';
				$server['token_url'] = 'https://login.live.com/oauth20_token.srf';
				$this->_accessTokenName = 'access_token';
				break;

			case 'salesforce':
				$server['auth_url'] = 'https://login.salesforce.com/services/oauth2/authorize
											?response_type=code
											&client_id={CLIENT_ID}
											&redirect_uri={REDIRECT_URI}
											&scope={SCOPE}
											&state={STATE}';
				$server['token_url'] = 'https://login.salesforce.com/services/oauth2/token';
				$this->_accessTokenName = 'access_token';
				break;

			case 'twitter':
				throw new OAuth2Exception('Twitter doesn\'t support OAuth2 protocol.');
				//$server['auth_url'] = '';
				//$server['token_url'] = 'https://api.twitter.com/oauth2/token';
				break;

			default:
				throw new OAuth2Exception('Invalid server name provided "' . $this->_serverName . '"');
				break;
		}

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

		$server['auth_url'] = str_replace(array_keys($vars), array_values($vars), $server['auth_url']);

		return $server;
	}

	/**
	 * Returns the authentication url.
	 *
	 * @return string Authentication url
	 */
	function getAuthenticationUrl() {
		$oauthServer = $this->_getOAuth2Server();

		return $oauthServer['auth_url'];
	}

	/**
	 * Returns the name of access token param. Its usually either 'access_token' or 'token' based on the OAuth2 server.
	 *
	 * @return string
	 */
	function getAccessTokenName() {
		return $this->_accessTokenName;
	}

	/**
	 * Returns the name of current OAuth2 server.
	 *
	 * @return string
	 */
	function getServerName(){
		return $this->_serverName;
	}
}