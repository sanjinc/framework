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

/**
 * Url standard object
 *
 * @package         WebinyFramework
 * @category		StdLib
 * @subcategory		StdObject
 */
 
class UrlObject implements \WF\StdLib\StdObject\StdObjectInterface
{
	use \WF\StdLib\StdLib;

	private $_url;

	private $_schema 	= false;
	private $_host 		= false;
	private $_port 		= '';
	private $_path		= '';
	private $_query 	= array();

	/**
	 * Constructor.
	 * Set standard object value.
	 *
	 * @param string $value
	 */
	public function __construct(&$value)
	{
		$value = $this->str($value)->lower()->trim();
		$this->_url = $value->getValue();
		$this->_validateUrl();
	}

	/**
	 * Returns host name, without trailing slash.
	 *
	 * @return bool|string
	 */
	public function getHost()
	{
		return $this->_host;
	}

	/**
	 * Returns schema (eg. http).
	 *
	 * @return bool|string
	 */
	public function getSchema()
	{
		return $this->_schema;
	}

	/**
	 * Returns port number.
	 *
	 * @return bool|int
	 */
	public function getPort()
	{
		return $this->_port;
	}

	/**
	 * Returns query params as an array from current object.
	 *
	 * @return array
	 */
	public function getQuery()
	{
		return $this->_query;
	}

	/**
	 * Returns the domain name of the current url.
	 *
	 * @return string|bool
	 */
	public function getDomain()
	{
		if($this->getSchema() && $this->getHost())
		{
			return $this->getSchema().'://'.$this->getHost();
		}

		return false;
	}

	/**
	 * Returns path from the current url.
	 *
	 * @return string
	 */
	public function getPath()
	{
		return $this->_path;
	}

	/**
	 * Return current standard objects value.
	 *
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->_url;
	}

	/**
	 * Returns the current standard object instance.
	 *
	 * @return UrlObject
	 */
	public function getObject()
	{
		return $this;
	}

	/**
	 * To string implementation.
	 *
	 * @return mixed
	 */
	public function __toString()
	{
		return $this->getValue();
	}

	/**
	 * Validates current url and parses data like schema, host, query, and similar from, it.
	 */
	private function _validateUrl()
	{
		$urlData = parse_url($this->getValue());
		if(!$urlData || !$this->isArray($urlData))
		{
			$this->exception("Unable to create a Url Standard Object from the given value. The value isn't a valid URL");
		}

		// extract parts
		$urlData = $this->arr($urlData);

		// schema
		$this->_schema = $urlData->key('schema');
		// host
		$this->_host = $urlData->key('host');
		// port
		$this->_port = $urlData->key('port', '');
		// path
		$this->_path = $urlData->key('path', '');

		// parse query string
		if($urlData->key('query'))
		{
			parse_str($urlData->key('query'), $queryData);
			if($this->isArray($queryData))
			{
				$this->_query = $queryData;
			}
		}
	}

	/**
	 * The update value method is called after each modifier method.
	 * It updates the current value of the standard object.
	 */
	function updateValue(&$value)
	{
		$this->_url = $value;
	}
}