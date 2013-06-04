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
 * Post Http component.
 *
 * @package		 Webiny\Component\Http
 */

class Post{
	use StdLibTrait;

	private $_postBag;

	function __construct(){
		$this->_postBag = $this->arr($_POST);
	}

	function get($key, $value=null){
		return $this->_postBag->key($key, $value, true);
	}

	function getAll(){
		return $this->_postBag->val();
	}
}