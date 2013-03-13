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

/**
 * Validator methods for array standard object.
 *
 * @package         WebinyFramework
 * @category		StdLib
 * @subcategory		StdObject
 */
 
trait Validator
{
	use \WF\StdLib\StdObject\StdObjectValidatorTrait;

	/**
	 * @return ArrayObject
	 */
	abstract function getObject();

	/**
	 * Checks if the $key is present inside the current array.
	 * If the key is present, the value under that key is returned, else the $default is returned.
	 *
	 * @param string|int $key
	 * @param mixed $default
	 * @return bool|mixed
	 */
	public function key($key, $default=false)
	{
		if($this->val($this->getValue()[$key]))
		{
			return $this->getValue()[$key];
		}

		return $default;
	}
}