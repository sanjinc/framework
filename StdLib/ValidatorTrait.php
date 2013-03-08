<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 * @package   WebinyFramework
 */

namespace WF\StdLib;

/**
 * Trait containing common validators
 *
 * @package         WebinyFramework
 * @category		StdLib
 */
 
trait ValidatorTrait
{
	/**
	 * Checks if given value is null.
	 *
	 * @param mixed $var Value to check
	 * @return bool
	 */
	static public function isNull(&$var)
	{
		return is_null($var);
	}

	/**
	 * Check if given value is an object.
	 *
	 * @param mixed $var Value to check
	 * @return bool
	 */
	static public function isObject(&$var)
	{
		return is_object($var);
	}

	/**
	 * Checks if given value is an array.
	 *
	 * @param $var
	 * @return bool
	 */
	static public function isArray(&$var)
	{
		return is_array($var);
	}

	/**
	 * Generate a hash value of the given string using the defined algorithm.
	 *
	 * @param string $string	String from which the hash will be calculated.
	 * @param string $algo		Name of the algorithm used for calculation (md5, sh1, ripemd160,...).
	 * @return string
	 */
}