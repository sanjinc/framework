<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 * @package   WebinyFramework
 */

namespace WF\StdLib\StdObject\UrlObject;

use WF\StdLib\StdObject\StdObjectException;
use WF\StdLib\StdObjectTrait;
use WF\StdLib\ValidatorTrait;
use WF\StdLib\StdObject\StdObjectAbstract;

/**
 * Url standard object.
 * If you want to extract parameters from a url, or to build/change its parts, this is a class for that.
 *
 * @package         WebinyFramework
 */

class UrlObject extends \WF\StdLib\StdObject\UrlObject
{
	use ValidatorTrait,
		StdObjectTrait;

	protected $_value;

	private $_scheme = false;
	private $_host = false;
	private $_port = '';
	private $_path = '';
	private $_query = array();


	/**
	 * Constructor.
	 * Set standard object value.
	 *
	 * @param string $value
	 */
	public function __construct($value) {
		$value = $this->str($value)->caseLower()->trim();
		$this->_value = $value->val();
		$this->_validateUrl();
	}

	/**
	 * Returns host name, without trailing slash.
	 *
	 * @return bool|string
	 */
	public function getHost() {
		return $this->_host;
	}

	/**
	 * Returns scheme (eg. http).
	 *
	 * @return bool|string
	 */
	public function getScheme() {
		return $this->_scheme;
	}

	/**
	 * Returns port number.
	 *
	 * @return bool|int
	 */
	public function getPort() {
		return $this->_port;
	}

	/**
	 * Returns query params as an array from current object.
	 *
	 * @return array
	 */
	public function getQuery() {
		return $this->_query;
	}

	/**
	 * Returns the domain name of the current url.
	 *
	 * @return string|bool
	 */
	public function getDomain() {
		if($this->getScheme() && $this->getHost()) {
			return $this->getScheme() . '://' . $this->getHost();
		}

		return false;
	}

	/**
	 * Returns path from the current url.
	 *
	 * @return string
	 */
	public function getPath() {
		return $this->_path;
	}

	/**
	 * Return, or update, current standard objects value.
	 *
	 * @param null|string $url
	 *
	 * @return mixed
	 */
	public function val($url=null){
		if($this->isNull($url)){
			return $this->_value;
		}

		$this->_value = $url;
		$this->_validateUrl();

		return $this;
	}

	/**
	 * To string implementation.
	 *
	 * @return mixed
	 */
	public function __toString() {
		return $this->val();
	}

	/**
	 * Validates current url and parses data like scheme, host, query, and similar from, it.
	 *
	 * @throws StdObjectException
	 */
	private function _validateUrl() {
		$urlData = parse_url($this->val());
		if(!$urlData || !$this->isArray($urlData)) {
			throw new StdObjectException("UrlObject: Given string is not a valid URL.");
		}

		// extract parts
		$urlData = $this->arr($urlData);

		// scheme
		$this->_scheme = $urlData->key('scheme', '', true);
		// host
		$this->_host = $urlData->key('host', '', true);
		// port
		$this->_port = $urlData->key('port', '', true);
		// path
		$this->_path = $urlData->key('path', '', true);

		// parse query string
		if($urlData->keyExists('query')) {
			parse_str($urlData->key('query'), $queryData);
			if($this->isArray($queryData)) {
				$this->_query = $queryData;
			}
		}
	}

	/**
	 * Builds url from current url elements.
	 *
	 * @return $this
	 */
	private function _buildUrl() {
		$url = http_build_url(array(
								   "scheme" => $this->_scheme,
								   "host"   => $this->_host,
								   "path"   => $this->_path,
								   "query"  => $this->_query
							  ));

		$this->val($url);

		return $this;
	}
}