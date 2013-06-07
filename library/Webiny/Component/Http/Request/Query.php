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
 * Query Http component.
 *
 * @package		 Webiny\Component\Http
 */

class Query{
	use StdLibTrait;

	private $_queryBag;

	/**
	 * Constructor.
	 */
	function __construct(){
		$this->_queryBag = $this->arr($_GET);
	}

	/**
	 * Get the value from GET for the given $key.
	 *
	 * @param string $key   Key name.
	 * @param null   $value Default value that will be returned if the $key is not found.
	 *
	 * @return string Value under the defined $key.
	 */
	function get($key, $value=null){
		return $this->_queryBag->key($key, $value, true);
	}

	/**
	 * Returns a list of all GET values.
	 *
	 * @return array
	 */
	function getAll(){
		return $this->_queryBag->val();
	}

}