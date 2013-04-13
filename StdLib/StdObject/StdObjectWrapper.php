<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace WF\StdLib\StdObject;

use WF\StdLib\StdObject\ArrayObject\ArrayObject;
use WF\StdLib\StdObject\StringObject\StringObject;

/**
 * Standard object wrapper.
 * This class is used when we need to return a standard object, but none of the current available standard objects
 * fit the role.
 *
 * @package		 WF\StdLib\StdObject
 */
class StdObjectWrapper extends StdObjectAbstract{

	protected $_value = null;

	/**
	 * Constructor.
	 * Set standard object value.
	 *
	 * @param mixed $value
	 */
	function __construct($value) {
		$this->_value = $value;
	}

	/**
	 * This function make sure you are returning a standard object.
	 *
	 * @param mixed $var
	 *
	 * @return ArrayObject|StdObjectWrapper|StringObject
	 */
	static function returnStdObject(&$var){
		// check if $var is already a standard object
		if(self::isInstanceOf($var, 'WF\StdLib\StdObject\StdObjectAbstract')){
			return $var;
		}

		// try to map $var to a standard object
		if(self::isString($var)){
			return new StringObject($var);
		}else if(self::isArray($var)){
			return new ArrayObject($var);
		}

		// return value as StdObjectWrapper
		return new self($var);
	}

	/**
	 * To string implementation.
	 *
	 * @return mixed
	 */
	function __toString() {
		echo 'StdObjectWrapper';
	}
}