<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Http\Request;

use Webiny\StdLib\StdLibTrait;

/**
 * Env Http component.
 *
 * @package         Webiny\Component\Http
 */

class Env
{
	use StdLibTrait;

	private $_envBag;

	/**
	 * Constructor.
	 */
	function __construct() {
		$this->_envBag = $this->arr($_ENV);
	}

	/**
	 * Get the value from environment variables for the given $key.
	 *
	 * @param string $key   Key name.
	 * @param null   $value Default value that will be returned if the $key is not found.
	 *
	 * @return string Value under the defined $key.
	 */
	function get($key, $value = null) {
		return $this->_envBag->key($key, $value, true);
	}

	/**
	 * Returns a list of all environment variables.
	 *
	 * @return array
	 */
	function getAll() {
		return $this->_envBag->val();
	}
}