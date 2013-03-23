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
}