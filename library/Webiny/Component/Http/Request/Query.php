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

	function __construct(){
		$this->_queryBag = $this->arr($_GET);
	}

	function get($key, $value=null){
		return $this->_queryBag->key($key, $value, true);
	}

	function getAll(){
		return $this->_queryBag->val();
	}

}