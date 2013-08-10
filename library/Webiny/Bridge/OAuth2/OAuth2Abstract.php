<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Bridge\OAuth2;

use Webiny\Component\StdLib\StdLibTrait;

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