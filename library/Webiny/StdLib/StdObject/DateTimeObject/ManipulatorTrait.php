<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\StdLib\StdObject\DateTimeObject;

use Exception;
use Webiny\StdLib\StdObject\StdObjectException;
use Webiny\StdLib\StdObject\StdObjectManipulatorTrait;
use Webiny\StdLib\StdObject\StringObject\StringObject;

/**
 * DateTimeObject manipulator trait.
 *
 * @package         Webiny\StdLib\StdObject\DateTimeObject
 */
trait ManipulatorTrait
{
	use StdObjectManipulatorTrait;

	/**
	 * Adds an amount of days, months, years, hours, minutes and seconds to a DateTimeObject.
	 *
	 * @param string $amount You can specify the amount in ISO8601 format (example: 'P14D' = 14 days; 'P1DT12H' = 1 day 12 hours),
	 *                       or as a date string (example: '1 day', '2 months', '3 year', '2 days + 10 minutes').
	 *
	 * @return $this
	 * @throws StdObjectException
	 */
	public function add($amount) {
		$interval = $this->_parseDateInterval($amount);
		$this->_getDateObject()->add($interval);

		return $this;
	}

	/**
	 * Set the date on current object.
	 *
	 * @param int $year
	 * @param int $month
	 * @param int $day
	 *
	 * @return $this
	 */
	public function setDate($year, $month, $day) {
		$this->_getDateObject()->setDate($year, $month, $day);

		return $this;
	}

	/**
	 * Set the time on current object.
	 *
	 * @param int $hour
	 * @param int $minute
	 * @param int $second
	 *
	 * @return $this
	 */
	public function setTime($hour, $minute, $second = 0) {
		$this->_getDateObject()->setTime($hour, $minute, $second);

		return $this;
	}

	/**
	 * Set the timestamp on current object.
	 *
	 * @param int $timestamp UNIX timestamp.
	 *
	 * @return $this
	 */
	public function setTimestamp($timestamp) {
		$this->_getDateObject()->setTimestamp($timestamp);

		return $this;
	}

	/**
	 * Subtracts an amount of days, months, years, hours, minutes and seconds from current DateTimeObject.
	 *
	 * @param string $amount You can specify the amount in ISO8601 format (example: 'P14D' = 14 days; 'P1DT12H' = 1 day 12 hours),
	 *                       or as a date string (example: '1 day', '2 months', '3 year', '2 days + 10 minutes').
	 *
	 * @return $this
	 * @throws StdObjectException
	 */
	public function sub($amount) {
		$interval = $this->_parseDateInterval($amount);
		$this->_getDateObject()->sub($interval);

		return $this;
	}


	/**
	 * Offsets the date object from current timezone to defined $timezone.
	 * This is an alias of DateTimeObject::setTimezone.
	 *
	 * @param string|\DateTimeZone $timezone Timezone to which you wish to offset. You can either pass \DateTimeZone object
	 *                                       or a valid timezone string. For timezone string formats
	 *                                       visit: http://php.net/manual/en/timezones.php
	 *
	 * @throws Exception|\Webiny\StdLib\StdObject\StdObjectException
	 * @return $this
	 */
	public function offsetToTimezone($timezone) {
		try {
			$this->setTimezone($timezone);
		} catch (StdObjectException $e) {
			throw $e;
		}

		return $this;
	}

	/**
	 * @param $interval
	 *
	 * @return \DateInterval
	 * @throws StdObjectException
	 */
	private function _parseDateInterval($interval) {
		try {
			if(!$this->isInstanceOf($interval, 'DateInterval')) {
				$interval = new StringObject($interval);
				if($interval->startsWith('P')) {
					$interval = new \DateInterval($interval);
				} else {
					$interval = \DateInterval::createFromDateString($interval);
				}
			}
		} catch (Exception $e) {
			throw new StdObjectException('DateTimeObject: Unable to read the given $amount as date inverval.', 0, $e);
		}

		return $interval;
	}
}