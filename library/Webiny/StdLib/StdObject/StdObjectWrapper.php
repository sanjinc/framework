<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\StdLib\StdObject;

use Webiny\StdLib\StdObject\ArrayObject\ArrayObject;
use Webiny\StdLib\StdObject\StringObject\StringObject;
use Webiny\StdLib\ValidatorTrait;

/**
 * Standard object wrapper.
 * This class is used when we need to return a standard object, but none of the current available standard objects
 * fit the role.
 *
 * @package		 Webiny\StdLib\StdObject
 */
class StdObjectWrapper extends StdObjectAbstract{
	use ValidatorTrait;

	protected $_value = null;
	static private $_returnType = 'StdObject'; // can be 'native' or 'StdObject'

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
		if(self::isInstanceOf($var, 'Webiny\StdLib\StdObject\StdObjectAbstract')){
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
	 * Returns a string based on given $var.
	 * This function checks if $var is a string, StringObject or something else. In the end a string is returned.
	 *
	 * @param mixed $var
	 *
	 * @return string
	 */
	static function toString($var){
		if(self::isString($var)){
			return $var;
		}else if(self::isObject($var)){
			if(self::isStringObject($var) || self::isFileObject($var)){
				return $var->val();
			}
		}

		return (string) $var;
	}

	/**
	 * Returns an array based on given $var.
	 * This function checks if $var is an array, ArrayObject or something else. This function tries to cast the element
	 * to array and return it.
	 *
	 * @param mixed $var
	 *
	 * @return array
	 */
	static function toArray($var){
		if(self::isArray($var)){
			return $var;
		}else if(self::isObject($var)){
			if(self::isInstanceOf($var, 'Webiny\StdLib\StdObject\ArrayObject\ArrayObject')){
				return $var->val();
			}
		}

		return (array) $var;
	}

	/**
	 * Check if $var is an instance of ArrayObject.
	 *
	 * @param mixed $var Element to check.
	 *
	 * @return bool
	 */
	static function isArrayObject($var){
		if(self::isInstanceOf($var, 'Webiny\StdLib\StdObject\ArrayObject\ArrayObject')){
			return true;
		}

		return false;
	}

	/**
	 * Check if $var is an instance of StringObject.
	 *
	 * @param mixed $var Element to check.
	 *
	 * @return bool
	 */
	static function isStringObject($var){
		if(self::isInstanceOf($var, 'Webiny\StdLib\StdObject\StringObject\StringObject')){
			return true;
		}

		return false;
	}

	/**
	 * Check if $var is an instance of UrlObject.
	 *
	 * @param mixed $var Element to check.
	 *
	 * @return bool
	 */
	static function isUrlObject($var){
		if(self::isInstanceOf($var, 'Webiny\StdLib\StdObject\UrlObject\UrlObject')){
			return true;
		}

		return false;
	}

    /**
     * Check if $var is an instance of FileObject.
     *
     * @param mixed $var Element to check.
     *
     * @return bool
     */
    static function isFileObject($var){
        if(self::isInstanceOf($var, 'Webiny\StdLib\StdObject\FileObject\FileObject')){
            return true;
        }

        return false;
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