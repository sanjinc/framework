<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Http\Request;

use Webiny\Component\StdLib\StdLibTrait;

/**
 * Request headers.
 *
 * @package		 Webiny\Component\Http\Request
 */
 
class Headers{
	use StdLibTrait;

	private $_headerBag;

	/**
	 * Constructor.
	 */
	function __construct() {
		$headers = getallheaders();
		$this->_headerBag = $this->arr($headers);
	}

	/**
	 * Get the value from header variables for the given $key.
	 *
	 * @param string $key   Key name.
	 * @param null   $value Default value that will be returned if the $key is not found.
	 *
	 * @return string Value under the defined $key.
	 */
	function get($key, $value = null) {
		return $this->_headerBag->key($key, $value, true);
	}

	/**
	 * Returns a list of all header variables.
	 *
	 * @return array
	 */
	function getAll() {
		return $this->_headerBag->val();
	}
}