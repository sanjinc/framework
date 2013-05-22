<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 * @package   WebinyFramework
 */

namespace Webiny\StdLib;

use Webiny\StdLib\StdObject\StdObjectWrapper;
use Webiny\StdLib\StdObject\StringObject\StringObject;
use Webiny\StdLib\StdObject\DateTimeObject\DateTimeObject;

/**
 * Trait containing common validators.
 *
 * @package         Webiny\StdLib
 */
trait ValidatorTrait
{
	static protected function is($var) {
		if(isset($var)) {
			return true;
		}

		return false;
	}

	/**
	 * Checks if given value is null.
	 *
	 * @param mixed $var Value to check
	 *
	 * @return bool
	 */
	static protected function isNull($var) {
		return is_null($var);
	}

	/**
	 * Check if given value is an object.
	 *
	 * @param mixed $var Value to check
	 *
	 * @return bool
	 */
	static protected function isObject($var) {
		return is_object($var);
	}

	/**
	 * Checks if given value is an array.
	 *
	 * @param $var
	 *
	 * @return bool
	 */
	static protected function isArray($var) {
		return is_array($var);
	}

	/**
	 * Checks if value is a number.
	 *
	 * @param $var
	 *
	 * @return bool
	 */
	static protected function isNumber($var) {
		return is_numeric($var);
	}

	/**
	 * Checks if value is an integer.
	 *
	 * @param $var
	 *
	 * @return bool
	 */
	static protected function isInteger($var) {
		return is_int($var);
	}

	/**
	 * Checks whenever resource is callable.
	 *
	 * @param $var
	 *
	 * @return bool
	 */
	static protected function isCallable($var) {
		return is_callable($var);
	}

	/**
	 * Checks if $var is type of string.
	 *
	 * @param $var
	 *
	 * @return bool
	 */
	static protected function isString($var) {
		return is_string($var);
	}

	/**
	 * Checks if $var is type of boolean.
	 *
	 * @param $var
	 *
	 * @return bool
	 */
	static protected function isBool($var) {
		return is_bool($var);
	}

	/**
	 * This is an alias function for self::isBool
	 *
	 * @param $var
	 *
	 * @return bool
	 */
	static protected function isBoolean($var){
		return self::isBool($var);
	}

    /**
     * Checks if $var is a file.
     *
     * @param $var
     *
     * @return bool
     */
    static protected function isFile($var) {
        return is_file($var);
    }

    /**
     * Checks if $var is a directory.
     *
     * @param $var
     *
     * @return bool
     */
    static protected function isDirectory($var) {
        return is_dir($var);
    }

	/**
	 * Check if $instance if of $type.
	 *
	 * @param $instance
	 * @param $type
	 *
	 * @return bool
	 */
	static protected function isInstanceOf($instance, $type) {
		return ($instance instanceof $type);
	}

	/**
	 * Check if $instance is a StandardObject.
	 *
	 * @param mixed $instance
	 *
	 * @return bool
	 */
	static protected function isStdObject($instance) {
		if(self::isInstanceOf($instance, 'Webiny\StdLib\StdObject\StdObjectAbstract')) {
			return true;
		}

		return false;
	}

    /**
     * Check if $instance is a StringObject.
     *
     * @param mixed $instance
     *
     * @return bool
     */
    static protected function isStringObject($instance) {
        return StdObjectWrapper::isStringObject($instance);
    }

	/**
	 * Check if $instance is a DateTimeObject.
	 *
	 * @param mixed $instance
	 *
	 * @return bool
	 */
	static protected function isDateTimeObject($instance) {
		return StdObjectWrapper::isDateTimeObject($instance);
	}

    /**
     * Check if $instance is a FileObject.
     *
     * @param mixed $instance
     *
     * @return bool
     */
    static protected function isFileObject($instance) {
        return StdObjectWrapper::isFileObject($instance);
    }

    /**
     * Check if $instance is an ArrayObject.
     *
     * @param mixed $instance
     *
     * @return bool
     */
    static protected function isArrayObject($instance) {
        return StdObjectWrapper::isArrayObject($instance);
    }

	/**
	 * Check if $instance is a UrlObject.
	 *
	 * @param mixed $instance
	 *
	 * @return bool
	 */
	static protected function isUrlObject($instance) {
		return StdObjectWrapper::isUrlObject($instance);
	}

	/**
	 * Checks if class exists.
	 * This function autoloads classes to checks if they exist.
	 *
	 * @param string $className Class name with their full namespace.
	 *
	 * @return bool
	 */
	static protected function classExists($className) {
		return class_exists($className, true);
	}
}