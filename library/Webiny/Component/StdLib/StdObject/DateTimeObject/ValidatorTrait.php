<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\StdLib\StdObject\DateTimeObject;

/**
 * DateObject validator
 *
 * @package         Webiny\Component\StdLib\StdObject\DateObject
 */

trait ValidatorTrait
{

	use \Webiny\Component\StdLib\ValidatorTrait;

	/**
	 * Check if current DateTimeObject is a leap year.
	 *
	 * @return string Boolean true if it's a leap year.
	 */
	function isLeap() {
		if(date('L', $this->getTimestamp()) > 0) {
			return true;
		}

		return false;
	}

	/**
	 * Check if current DateTimeObject is in future.
	 *
	 * @return bool Boolean true if the date is in the future. Otherwise false.
	 */
	function isFuture() {
		if($this->getTimestamp() > time()) {
			return true;
		}

		return false;
	}

	/**
	 * Check if current DateTimeObject is in past.
	 *
	 * @return bool Boolean true if the date is in the past. Otherwise false.
	 */
	function isPast() {
		if($this->isFuture()) {
			return false;
		}

		return true;
	}

	/**
	 * Check if current datetime is larger than $time.
	 *
	 * @param int|string|\DateTime|DateTimeObject $time     Date to compare to.
	 *
	 * @return bool Boolean true if current date object is larger than the provided $time.
	 */
	function largerThan($time) {
		$diff = $this->diff($time, false);
		if($diff->key('invert') <= 0) {
			return false;
		}

		return true;
	}

	/**
	 * Check if current datetime smaller than $time.
	 *
	 * @param int|string|\DateTime|DateTimeObject $time     Date to compare to.
	 *
	 * @return bool Boolean true if current date object is smaller than the provided $time.
	 */
	function smallerThan($time) {
		if($this->largerThan($time)) {
			return false;
		}

		return true;
	}
}