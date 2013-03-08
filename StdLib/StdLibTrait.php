<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 * @package	  WebinyFramework
 */

namespace WF\StdLib;

/**
 * A library of standard functions
 *
 * @package         WebinyFramework
 * @category		StdLib
 */
 
trait StdLibTrait
{
	use ExceptionTrait, StdObjectTrait;

	/**
	 * Checks if $var is set and that is not null.
	 * If that is true, the $var is returned, else $returnNull is returned (default: false).
	 * NOTE: Empty value will return true.
	 *
	 * @param mixed $var
	 * @param mixed $returnNull
	 * @return mixed
	 */
	static public function val(&$var, $returnNull=false)
	{
		if(!isset($var) || is_null($var))
		{
			return $returnNull;
		}

		return $var;
	}

	static public function hash($string, $algo='sh1')
	{
		return hash($algo, $string);
	}
}