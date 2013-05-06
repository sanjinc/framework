<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 * @package   WebinyFramework
 */

namespace Webiny\StdLib\StdObject\ArrayObject;

use Webiny\StdLib\StdObject\StdObjectException;
use Webiny\StdLib\StdObject\StdObjectValidatorTrait;
use Webiny\StdLib\StdObject\StringObject\StringObject;

/**
 * Validator methods for array standard object.
 *
 * @package         Webiny\StdLib\StdObject\ArrayObject
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
	public function inArray($value, $strict = false) {
		return in_array($value, $this->val(), $strict);
	}

	/**
	 * Checks if $key exists in current array as index. If it exists, true is returned.
	 * If the $key doesn't exist, $default is returned,
	 *
	 * @param string $key     Array key.
	 * @param mixed  $default If key is not found, $default is returned.
	 *
	 * @return bool|mixed
	 */
	public function keyExists($key, $default = false) {
		if(array_key_exists($key, $this->val())) {
			return true;
		}

		return $default;
	}
}