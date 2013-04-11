<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 * @package   WebinyFramework
 */

namespace WF\StdLib\StdObject\ArrayObject;

use WF\StdLib\StdObject\StdObjectException;
use WF\StdLib\StdObject\StdObjectValidatorTrait;
use WF\StdLib\StdObject\StringObject\StringObject;

/**
 * Validator methods for array standard object.
 *
 * @package         WF\StdLib\StdObject\ArrayObject
 */

trait ValidatorTrait
{
	use StdObjectValidatorTrait;

	/**
	 * Search the array for the given $value.
	 * If $strict is true, both values must be of the same instance type.
	 *
	 * @param mixed $value
	 * @param bool  $strict
	 *
	 * @return bool|key Returns the key under which the $value is found, or false.
	 */
	public function search($value, $strict = false) {
		return array_search($value, $this->getValue(), $strict);
	}

	/**
	 * Return a value from the array for the given key.
	 * If the $key doesn't exist, $default is returned.
	 *
	 * @param string $key     Array key.
	 * @param mixed  $default If key is not found, $default is returned.
	 *
	 * @return mixed|StringObject
	 */
	public function key($key, $default = false) {
		if(array_key_exists($key, $this->getValue())) {
			if($this->isString($this->getValue()[$key]))
			{
				return new StringObject($this->getValue()[$key]);
			}else{
				return $this->getValue()[$key];
			}
		}

		return $default;
	}
}