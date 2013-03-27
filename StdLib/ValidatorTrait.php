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
 * @package         WF\StdLib
 */

trait ValidatorTrait
{
    /**
     * Checks if given value is null.
     *
     * @param mixed $var Value to check
     *
     * @return bool
     */
    static public function isNull(&$var) {
        return is_null($var);
    }

    /**
     * Check if given value is an object.
     *
     * @param mixed $var Value to check
     *
     * @return bool
     */
    static public function isObject(&$var) {
        return is_object($var);
    }

    /**
     * Checks if given value is an array.
     *
     * @param $var
     *
     * @return bool
     */
    static public function isArray(&$var) {
        return is_array($var);
    }

	/**
	 * Checks if value is a number.
	 *
	 * @param $var
	 *
	 * @return bool
	 */
	static public function isNumber(&$var){
		return is_numeric($var);
	}

	/**
	 * Checks whenever resource is callable.
	 *
	 * @param $var
	 *
	 * @return bool
	 */
	static public function isCallable(&$var)
	{
		return is_callable($var);
	}

	/**
	 * Check if $instance if of $type.
	 *
	 * @param $instance
	 * @param $type
	 *
	 * @return bool
	 */
	static public function isInstanceOf(&$instance, $type){
		return ($instance instanceof $type);
	}
}