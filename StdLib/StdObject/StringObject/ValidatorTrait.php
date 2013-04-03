<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 * @package   WebinyFramework
 */

namespace WF\StdLib\StdObject\StringObject;

use WF\StdLib\StdObject\ArrayObject\ArrayObject;
use WF\StdLib\StdObject\StdObjectException;
use WF\StdLib\StdObject\StdObjectValidatorTrait;

/**
 * String validator trait.
 *
 * @package         WebinyFramework
 * @category        StdLib
 * @subcategory        String
 */

trait ValidatorTrait
{
	use StdObjectValidatorTrait;

	/**
	 * Checks if a string contains the given $char.
	 * If the $char is present, true is returned.
	 * If you wish to match a string to a regular expression use StringObject:match().
	 *
	 * @param string|StringObject $needle String you wish to check if it exits within the current string.
	 *
	 * @throws StdObjectException
	 * @return bool
	 */
	public function contains($needle) {
		if(!$this->isString($needle) && !$this->isInstanceOf($needle, $this)) {
			throw new StdObjectException('StringObject: $needle must be a string or a StringObject.');
		}

		if($this->isInstanceOf($needle, $this)) {
			$needle = $needle->getValue();
		}

		if(stripos($this->getValue(), $needle) !== false) {
			return true;
		}

		return false;
	}

	/**
	 * Check if $string is equal to current string.
	 * Note that this comparison is case sensitive and binary safe.
	 *
	 * @param string|StringObject $string String to compare.
	 *
	 * @throws StdObjectException
	 * @return bool
	 */
	public function equals($string) {
		if($this->isInstanceOf($string, $this)) {
			$string = $string->getValue();
		} else {
			if(!$this->isString($string)) {
				throw new StdObjectException('StringObject: $string must be a string or a StringObject.');
			}
		}

		$result = strcmp($string, $this->getValue());
		if($result !== 0) {
			return false;
		}

		return true;
	}

	/**
	 * Returns the position of the given $string inside the current string object.
	 * Boolean false is returned if the $string is not present inside the current string.
	 * NOTE: Use type validation check in order no to mistake the position '0' (zero) for (bool) false.
	 *
	 * @param string $string
	 * @param int    $offset
	 *
	 * @throws StdObjectException
	 * @return int|bool
	 */
	public function stringPosition($string, $offset = 0) {
		if($this->isInstanceOf($string, $this)) {
			$string = $string->getValue();
		} else {
			if(!$this->isString($string)) {
				throw new StdObjectException('StringObject: $string must be a string or a StringObject.');
			}
		}

		if(!$this->isNumber($offset)) {
			throw new StdObjectException('StringObject: $offset must be an integer.');
		}

		return stripos($this->getValue(), $string, $offset);
	}

	/**
	 * Checks if the current string starts with the given $string.
	 *
	 * @param string|StringObject $string String to check.
	 *
	 * @throws StdObjectException
	 * @return bool
	 */
	public function startsWith($string) {
		if($this->isInstanceOf($string, $this)) {
			$string = $string->getValue();
		} else {
			if(!$this->isString($string)) {
				throw new StdObjectException('StringObject: $string must be a string or a StringObject.');
			}
		}

		$position = $this->stringPosition($string);
		if($position !== false && $position == 0) {
			return true;
		}

		return false;
	}

	/**
	 * Checks if the string length is great than the given length.
	 *
	 * @param int  $num
	 * @param bool $inclusive
	 *
	 * @return bool
	 */
	public function longerThan($num, $inclusive = false) {
		$length = strlen($this->getValue());
		if($length > $num) {
			return true;
		} else {
			if($inclusive && $length >= $num) {
				return true;
			}
		}

		return false;
	}
}