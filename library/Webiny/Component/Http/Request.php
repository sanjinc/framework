<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Http;

use Webiny\Component\Config\Config;
use Webiny\Component\Http\Request\Cookie;
use Webiny\Component\Http\Request\Env;
use Webiny\Component\Http\Request\File;
use Webiny\Component\Http\Request\Files;
use Webiny\Component\Http\Request\Headers;
use Webiny\Component\Http\Request\Query;
use Webiny\Component\Http\Request\Post;
use Webiny\Component\Http\Request\Server;
use Webiny\Component\Http\Request\Session;
use Webiny\Component\StdLib\SingletonTrait;
use Webiny\Component\StdLib\StdLibTrait;
use Webiny\Component\StdLib\StdObject\ArrayObject\ArrayObject;
use Webiny\Component\StdLib\StdObject\UrlObject\UrlObject;
use Webiny\WebinyTrait;

/**
 * Request class holds the information about current request.
 *
 * @package         Webiny\
 */

class Request
{
	use SingletonTrait, StdLibTrait, WebinyTrait;

	const HEADER_CLIENT_IP = 'X_FORWARDED_FOR';
	const HEADER_CLIENT_HOST = 'X_FORWARDED_HOST';
	const HEADER_CLIENT_PROTO = 'X_FORWARDED_PROTO';
	const HEADER_CLIENT_PORT = 'X_FORWARDED_PORT';

	/**
	 * @var ConfigObject
	 */
	private $_config;

	/**
	 * @var array Array of IPs from trusted proxies.
	 */
	private $_trustedProxies = [];

	/**
	 * @var string
	 */
	private $_currentUrl = '';

	/**
	 * @var ArrayObject
	 */
	private $_query;

	/**
	 * @var ArrayObject
	 */
	private $_post;

	/**
	 * @var Session
	 */
	private $_session;

	/**
	 * @var Cookie
	 */
	private $_cookie;

	/**
	 * @var Files
	 */
	private $_files;

	/**
	 * @var Server
	 */
	private $_server;

	/**
	 * @var Env
	 */
	private $_env;

	/**
	 * @var Headers
	 */
	private $_headers;

	/**
	 * This function prepare the Request and all of its sub-classes.
	 * This class is called automatically by SingletonTrait.
	 */
	function init() {

		$this->_config = $this->webiny()->getConfig()->components->http;

		$this->_query = new Query();
		$this->_post = new Post();
		$this->_session = new Session($this->_config->session);
		$this->_server = new Server();
		$this->_files = new Files();
		$this->_env = new Env();
		$this->_headers = new Headers();

		if(isset($this->_config->trusted_proxies)) {
			$this->_trustedProxies = $this->_config->trusted_proxies;
		}
	}

	/**
	 * Get a value from $_GET param for the given $key.
	 * If key doesn't not exist, $value will be returned and assigned under that key.
	 *
	 * @param string $key   Key for which you wish to get the value.
	 * @param mixed  $value Default value that will be returned if $key doesn't exist.
	 *
	 * @return mixed Value of the given $key.
	 */
	function query($key = null, $value = null) {
		return $this->isNull($key) ? $this->_query->getAll() : $this->_query->get($key, $value);
	}

	/**
	 * Get a value from $_POST param for the given $key.
	 * If key doesn't not exist, $value will be returned and assigned under that key.
	 *
	 * @param string $key   Key for which you wish to get the value.
	 * @param mixed  $value Default value that will be returned if $key doesn't exist.
	 *
	 * @return mixed Value of the given $key.
	 */
	function post($key = null, $value = null) {
		return $this->isNull($key) ? $this->_post->getAll() : $this->_post->get($key, $value);
	}

	/**
	 * Get a value from $_ENV param for the given $key.
	 * If key doesn't not exist, $value will be returned and assigned under that key.
	 *
	 * @param string $key   Key for which you wish to get the value.
	 * @param mixed  $value Default value that will be returned if $key doesn't exist.
	 *
	 * @return mixed Value of the given $key.
	 */
	function env($key = null, $value = null) {
		return $this->isNull($key) ? $this->_env->getAll() : $this->_env->get($key, $value);
	}

	/**
	 * Access to the $_SERVER parameter over a object wrapper.
	 *
	 * @return Server
	 */
	function server() {
		return $this->_server;
	}

	/**
	 * Get current session handler instance.
	 *
	 * @return Session
	 */
	function session() {
		return $this->_session;
	}

	/**
	 * Access to cookie class.
	 * The cookie class is created the first time you need to access cookies because the Request object must be fully
	 * configured before you can use Cookie class.
	 *
	 * @throws \Exception|Request\Cookie\CookieException
	 * @return Cookie
	 */
	function cookie() {
		if(!isset($this->_cookie)) {
			try {
				$this->_cookie = new Cookie($this->_config->cookie);
			} catch (Cookie\CookieException $e) {
				throw $e;
			}

		}

		return $this->_cookie;
	}

	/**
	 * Get the File object for the given $name.
	 * If you have a multi-dimensional upload field name, than you should pass the optional $arrayOffset param to get the
	 * right File object.
	 *
	 * @param string   $name        Name of the upload field.
	 * @param null|int $arrayOffset Optional array offset for multi-dimensional upload fields.
	 *
	 * @throws \Exception|Request\Files\FilesException
	 * @return Files\File
	 */
	function files($name, $arrayOffset = null) {
		try {
			return $this->_files->get($name, $arrayOffset);
		} catch (Files\FilesException $e) {
			throw $e;
		}

	}

	/**
	 * Array of IPs from trusted proxies.
	 * @return array
	 */
	function getTrustedProxies() {
		return $this->_trustedProxies;
	}

	/**
	 * Get a list of trusted headers.
	 *
	 * @return array List of trusted headers.
	 */
	function getTrustedHeaders() {
		return [
			'client_ip'    => isset($this->_config->client_ip) ? $this->_config->client_ip : self::HEADER_CLIENT_IP,
			'client_host'  => isset($this->_config->client_host) ? $this->_config->client_host : self::HEADER_CLIENT_HOST,
			'client_proto' => isset($this->_config->client_proto) ? $this->_config->client_proto : self::HEADER_CLIENT_PROTO,
			'client_port'  => isset($this->_config->client_port) ? $this->_config->client_port : self::HEADER_CLIENT_PORT,
		];
	}

	/**
	 * Get current url with schema, host, port, request uri and query string.
	 * You can get the result in a form of a string or as a url standard object.
	 *
	 * @param bool $asUrlObject In which format you want to get the result, url standard object or a string.
	 *
	 * @return string|\Webiny\Component\StdLib\StdObject\UrlObject\UrlObject Current url.
	 */
	function getCurrentUrl($asUrlObject = false) {
		if($this->_currentUrl == '') {
			// schema
			$pageURL = 'http';
			if($this->isRequestSecured()) {
				$pageURL = 'https';
			}
			$pageURL .= "://";

			// port, server name and request uri
			$port = $this->getConnectionPort();
			$host = $this->getHostName();
			/*if($port && $port != "80") {
				$pageURL .= $host . ":" . $port . $this->server()->requestUri();
			} else {
				$pageURL .= $host . $this->server()->requestUri();
			}*/
			$pageURL .= $host . $this->server()->requestUri();

			// query
			$query = $this->server()->queryString();
			if($query) {
				$pageURL .= '?' . $query;
			}

			$this->_currentUrl = $pageURL;
		}

		if($asUrlObject) {
			return $this->url($this->_currentUrl);
		} else {
			return $this->_currentUrl;
		}
	}

	/**
	 * Get client ip address.
	 * This function check and validates headers from trusted proxies.
	 *
	 * @throws RequestException
	 * @return string Client IP address.
	 */
	function getClientIp() {
		$remoteAddress = $this->server()->remoteAddress();
		$fwdClientIp = $this->server()->get($this->getTrustedHeaders()['client_ip']);
		if($fwdClientIp && $remoteAddress && in_array($remoteAddress, $this->getTrustedProxies())) {
			// Use the forwarded IP address, typically set when the
			// client is using a proxy server.
			// Format: "X-Forwarded-For: client1, proxy1, proxy2"
			$clientIps = explode(',', $fwdClientIp);
			$clientIp = array_shift($clientIps);
		} elseif($this->server()->httpClientIp() && $remoteAddress && in_array($remoteAddress,
																			   $this->getTrustedProxies())
		) {
			// Use the forwarded IP address, typically set when the
			// client is using a proxy server.
			$clientIps = explode(',', $this->server()->httpClientIp());
			$clientIp = array_shift($clientIps);
		} elseif($this->server()->remoteAddress()) {
			// The remote IP address
			$clientIp = $this->server()->remoteAddress();
		} else {
			throw new RequestException('Unable to get client IP address.');
		}

		return $clientIp;
	}

	/**
	 * Check if connection is secured.
	 * This function check the forwarded headers from trusted proxies.
	 *
	 * @return bool True if connection is secured (https), otherwise false is returned.
	 */
	function isRequestSecured() {
		$remoteAddress = $this->server()->remoteAddress();

		$protocol = $this->server()->serverProtocol();
		$fwdProto = $this->server()->get($this->getTrustedHeaders()['client_proto']);
		if($fwdProto && $fwdProto != '' && in_array($remoteAddress, $this->getTrustedProxies())) {
			$protocol = $fwdProto;
		}
		$protocol = strtolower($protocol);

		return in_array($protocol, [
								   'https',
								   'on',
								   '1'
								   ]);
	}

	/**
	 * Return the connection port number.
	 * This function check the forwarded headers from trusted proxies.
	 *
	 * @return int Port number.
	 */
	function getConnectionPort() {
		$remoteAddress = $this->server()->remoteAddress();

		$port = $this->server()->serverPort();
		$fwdPort = $this->server()->get($this->getTrustedHeaders()['client_port']);
		if($fwdPort && $fwdPort != '' && in_array($remoteAddress, $this->getTrustedProxies())) {
			$port = $fwdPort;
		}

		return $port;
	}

	/**
	 * Returns the host name.
	 * This function check the forwarded headers from trusted proxies.
	 *
	 * @return string Host name
	 */
	function getHostName() {
		$remoteAddress = $this->server()->remoteAddress();

		$host = $this->server()->serverName();
		$fwdHost = $this->server()->get($this->getTrustedHeaders()['client_host']);
		if($fwdHost && $fwdHost != '' && in_array($remoteAddress, $this->getTrustedProxies())) {
			$host = $fwdHost;
		}

		return strtolower($host);
	}

	/**
	 * Redirect the request to the given url.
	 *
	 * @param string|UrlObject $url
	 * @param string|int|array       $headers Headers that you wish to send with your request.
	 */
	function redirect($url, $headers = null) {
		if(!$this->isStdObject($url)) {
			$url = $this->url($url);
		}

		$url->goToUrl($headers);
	}

	/**
	 * Checks if current request method is POST.
	 *
	 * @return bool True if it's POST.
	 */
	function isPost(){
		return $this->str($this->server()->requestMethod())->equals('POST');
	}

	/**
	 * Checks if current request method is GET.
	 *
	 * @return bool True if it's GET.
	 */
	function isGet(){
		return $this->str($this->server()->requestMethod())->equals('GET');
	}
}