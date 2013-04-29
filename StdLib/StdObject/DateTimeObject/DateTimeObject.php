<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace WF\StdLib\StdObject\DateTimeObject;

use WF\StdLib\StdObject\ArrayObject\ArrayObject;
use WF\StdLib\StdObject\StdObjectAbstract;
use WF\StdLib\StdObject\StdObjectException;
use WF\StdLib\StdObject\StringObject\StringObject;
use WF\StdLib\StdObject\DateTimeObject\ValidatorTrait;

/**
 * Date standard object.
 * Class that enables you to work with dates and time much easier.
 *
 * @package         WF\StdLib\StdObject\DateTimeObject
 */
class DateTimeObject extends StdObjectAbstract
{
	use ValidatorTrait, ManipulatorTrait;

	/**
	 * @var \DateTime|null
	 */
	protected $_value = null;

	/**
	 * This is the default timezone. It's set to the servers timezone.
	 * This object is static because we don't want to detect the default timezone each time.
	 * @var null|\DateTimeZone
	 */
	static private $_defaultTimezone = null;

	/**
	 * Default date format
	 * @var string
	 */
	static private $_defaultFormat = 'Y-m-d H:i:s';

	/**
	 * @var Timezone of entry date timestamp.
	 */
	private $_timezone = null;

	/**
	 * @var string Date format
	 */
	private $_format = null;

	/**
	 * A list of valid date formats grouped by their type.
	 * @var ArrayObject
	 */
	static private $_formatters = [
		'date'     => [
			'c',
			'r',
			'U'
		],
		'year'     => [
			'Y',
			'y',
			'o'
		],
		'month'    => [
			'F',
			'm',
			'M',
			'n',
			't'
		],
		'week'     => ['W'],
		'day'      => [
			'd',
			'D',
			'j',
			'l',
			'N',
			'S',
			'w',
			'z'
		],
		'time'     => ['H:i:s'],
		'hours'    => [
			'g',
			'G',
			'h',
			'H'
		],
		'meridiem' => [
			'a',
			'A'
		],
		'minutes'  => ['i'],
		'seconds'  => ['s']
	];
	/**
	 * @var null|ArrayObject
	 */
	private $_dateTimeFormat = null;


	/**
	 * Constructor.
	 * Set standard object value.
	 *
	 * @param string|int  $time                     A date/time string. List of available formats is explained here
	 *                                              http://www.php.net/manual/en/datetime.formats.php
	 * @param null|string $timezone                 Timezone in which you want to set the date. Here is a list of valid
	 *                                              timezones: http://php.net/manual/en/timezones.php
	 *
	 * @throws StdObjectException
	 * @internal param mixed $value
	 */
	public function __construct($time = "now", $timezone = null) {
		try {
			// set the config
			$this->_parseDateTimeFormat();

			// get date timezone
			$this->_entryTimezone = $this->_createTimezone($timezone);
			$this->val(new \DateTime($time, $this->_entryTimezone));

			// get UTC offset and correct the date to UTC by calculating the offset
			$this->_timestamp = $this->_getDateObject()->getTimestamp();
		} catch (\Exception $e) {
			throw new StdObjectException('DateTimeObject: Unable to create a DateTimeObject.', 0, $e);
		}
	}

	/**
	 * Set the date format.
	 *
	 * @param string $format Date format. These are the valid options: http://php.net/manual/en/function.date.php
	 *
	 * @return $this
	 */
	public function setFormat($format) {
		$this->_format = $format;

		return $this;
	}

	/**
	 * Set a new timezone for current date object.
	 * NOTE: The current timestamp will be recalculated with the offset of current timezone and the new defined one.
	 *
	 * @param string|\DateTimeZone $timezone Timezone to which you wish to offset. You can either pass \DateTimeZone object
	 *                                       or a valid timezone string. For timezone string formats
	 *                                       visit: http://php.net/manual/en/timezones.php
	 *
	 * @return $this
	 * @throws StdObjectException
	 */
	public function setTimezone($timezone) {
		try {
			if(!$this->isInstanceOf($timezone, 'DateTimeZone')){
				$timezone = new \DateTimeZone($timezone);
			}

			$this->_getDateObject()->setTimezone($timezone);
		} catch (\Exception $e) {
			throw new StdObjectException('DateTimeObject: Invalid timezone provided "' . $timezone . '"');
		}

		return $this;
	}

	/**
	 * Create a DateTimeObject from the given $time and $format.
	 *
	 * @param string|int  $time   Timestamp.
	 * @param null|string $format Format in which the current timestamp is defined.
	 *
	 * @return DateTimeObject
	 * @throws StdObjectException
	 */
	static public function createFromFormat($time, $format = null) {
		if(self::isNull($format)) {
			$format = self::$_defaultFormat;
		}

		try {
			$date = \DateTime::createFromFormat($format, $time);
			if(!$date) {
				throw new StdObjectException('DateTimeObject: Unable to create date from the given $time and $format');
			}
		} catch (\Exception $e) {
			throw new StdObjectException('DateTimeObject: Unable to create date from the given $time and $format.', 0, $e);
		}

		try {
			$dt = new DateTimeObject();
			$dt->setTimestamp($date->getTimestamp());
			$dt->setFormat($format);
		} catch (StdObjectException $e) {
			throw new StdObjectException('DateTimeObject: Unable to create DateTimeObject.', 0, $e);
		}

		return $dt;
	}

	/**
	 * @param int|string|\DateTime|DateTimeObject $time     Date to compare to.
	 * @param bool                                $absolute Should the interval be forced to be positive?
	 *
	 * @throws StdObjectException
	 * @return ArrayObject
	 */
	public function diff($time, $absolute = false) {
		try {
			if($this->isInstanceOf($time, 'DateTime')) {
				$date = $time;
			} else {
				if($this->isInstanceOf($time, $this)) {
					$date = new \DateTime();
					$date->setTimestamp($time->getTimestamp());
				} else {
					$date = new \DateTime($time);
				}
			}
		} catch (\Exception $e) {
			throw new StdObjectException('DateTimeObject: Unable to parse $time.', 0, $e);
		}

		try {
			$diff = $this->_getDateObject()->diff($date, $absolute);
		} catch (\Exception $e) {
			throw new StdObjectException('DateTimeObject: Unable to diff the two dates.', 0, $e);
		}

		$result = get_object_vars($diff);

		return new ArrayObject($result);
	}

	/**
	 * Return date in the given format.
	 *
	 * @param string $format A valid date format.
	 *
	 * @return string
	 * @throws StdObjectException
	 */
	public function format($format) {
		try {
			return $this->_getDateObject()->format($format);
		} catch (\Exception $e) {
			throw new StdObjectException('DateTimeObject: Unable to return date in the given format "' . $format . '".', 0, $e);
		}
	}

	/**
	 * Returns the offset from current timezone to the UTC timezone in seconds.
	 *
	 * @return int
	 */
	public function getOffset() {
		return $this->_getDateObject()->getOffset();
	}

	/**
	 * Returns the name of current timezone.
	 *
	 * @return \DateTimeZone
	 */
	public function getTimezone() {
		return $this->_getDateObject()->getTimezone()->getName();
	}

	/**
	 * Returns date in full date format.
	 *
	 * @param null|string $format A valid date format.
	 *
	 * @return string
	 */
	public function getDate($format = null) {
		return $this->_getDateElement('date', $format);
	}

	/**
	 * Return year based on current date.
	 *
	 * @param null|string $format A valid year format.
	 *
	 * @return string
	 */
	public function getYear($format = null) {
		return $this->_getDateElement('year', $format);
	}

	/**
	 * Return month based on current date.
	 *
	 * @param null|string $format A valid month format.
	 *
	 * @return string
	 */
	public function getMonth($format = null) {
		return $this->_getDateElement('month', $format);
	}

	/**
	 * Return week number based on current date.
	 *
	 * @return int
	 */
	public function getWeek() {
		return $this->_getDateElement('week');
	}

	/**
	 * Return day based on current date.
	 *
	 * @param null|string $format A valid day format.
	 *
	 * @return string
	 */
	public function getDay($format = null) {
		return $this->_getDateElement('day', $format);
	}

	/**
	 * Return time based on current date.
	 *
	 * @param null|string $format A valid time format.
	 *
	 * @return string
	 */
	public function getTime($format = null) {
		return $this->_getDateElement('time', $format);
	}

	/**
	 * Return hours based on current date.
	 *
	 * @param null|string $format A valid hour format.
	 *
	 * @return string
	 */
	public function getHours($format = null) {
		return $this->_getDateElement('hours', $format);
	}

	/**
	 * Return meridiem (am, pm) based on current date.
	 *
	 * @param null|string $format A valid meridiem format.
	 *
	 * @return string
	 */
	public function getMeridiem($format = null) {
		return $this->_getDateElement('meridiem', $format);
	}

	/**
	 * Return minutes based on current date.
	 *
	 * @return string
	 */
	public function getMinutes() {
		return $this->_getDateElement('minutes');
	}

	/**
	 * Return seconds based on current date.
	 *
	 * @return string
	 */
	public function getSeconds() {
		return $this->_getDateElement('seconds');
	}

	/**
	 * Return UNIX timestamp.
	 * @return int
	 */
	public function getTimestamp() {
		return $this->_getDateObject()->getTimestamp();
	}

	/**
	 * Calculates the time passed between current date and $form (default: now).
	 * The output is formatted in plain words, like "4 hours ago".
	 *
	 * @param null $from Timestamp from where to calculate the offset. Default is now.
	 *
	 * @return string
	 */
	public function getTimeAgo($from = null) {
		$periods = [
			'second',
			'minute',
			'hour',
			'day',
			'week',
			'month',
			'year',
			'decade'
		];
		$lengths = [
			'60',
			'60',
			'24',
			'7',
			'4.35',
			'12',
			'10'
		];

		$now = ($this->isNull($from)) ? time() : strtotime($from);
		$unix_date = $this->getTimestamp();

		// is it future date or past date
		if($now > $unix_date) {
			$difference = $now - $unix_date;
			$tense = "ago";

		} else {
			$difference = $unix_date - $now;
			$tense = "from now";
		}

		for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
			$difference /= $lengths[$j];
		}

		$difference = round($difference);

		if($difference != 1) {
			$periods[$j] .= "s";
		}

		return $difference . ' ' . $periods[$j] . ' ' . $tense;
	}

	/**
	 * Return, or update, current standard objects value.
	 *
	 * @param null $value If $value is set, value is updated and ArrayObject is returned.
	 *
	 * @return mixed
	 */
	public function val($value = null) {
		if(!$this->isNull($value)){
			$this->_value = $value;

			return $this;
		}

		return $this->_getDateObject()->format($this->_format);
	}

	/**
	 * To string implementation.
	 *
	 * @return mixed
	 */
	public function __toString() {
		return $this->format($this->_format);
	}

	/**
	 * Returns current \DateTime object.
	 *
	 * @return \DateTime|null
	 */
	private function _getDateObject(){
		return $this->_value;
	}

	/**
	 * Create a DateTimeZone object for the given $timeZone.
	 * If $timezone is undefined, default timezone is returned. Default timezone is the servers timezone.
	 *
	 * @param string|null $timezone A valid time zone. For list of available timezones visit:
	 *                              http://www.php.net/manual/en/timezones.php
	 *
	 * @return \DateTimeZone
	 * @throws StdObjectException
	 */
	private function _createTimezone($timezone = null) {
		try {
			if($this->isNull($timezone)) {
				if($this->isNull(self::$_defaultTimezone)) {
					try {
						$defaultTimezone = date_default_timezone_get();

						self::$_defaultTimezone = new \DateTimeZone($defaultTimezone);
					} catch (\Exception $e) {
						throw new StdObjectException('DateTimeObject: Unable to detect the default timezone.');
					}
				}

				return self::$_defaultTimezone;
			} else {
				return new \DateTimeZone($timezone);
			}
		} catch (\Exception $e) {
			throw new StdObjectException('DateTimeObject: Unable to create a valid time zone for given zone: ' . $timezone, 0, $e);
		}
	}

	/**
	 * This function parses the format provided by Config and sets the default formatting for getting date information
	 * like day, month, year, etc..
	 *
	 * @throws StdObjectException
	 */
	private function _parseDateTimeFormat() {
		try {
			if($this->isNull($this->_format)) {
				$this->_format = self::$_defaultFormat;
			}

			$str = new StringObject($this->_format);
			$chunks = $str->split();

			$this->_buildFormatterList();

			foreach ($chunks as $c) {
				foreach (self::$_formatters as $fk => $f) {
					if($f->inArray($c)) {
						$this->_dateTimeFormat[$fk] = $c;
					}
				}
			}
			$this->_dateTimeFormat = new ArrayObject($this->_dateTimeFormat);
		} catch (StdObjectException $e) {
			throw new StdObjectException('DateTimeObject: Unable to parse date/time format.', 0, $e);
		}
	}

	/**
	 * Reformats self::$_formatters from array to ArrayObject.
	 */
	private function _buildFormatterList() {
		if(!$this->isObject(self::$_formatters)) {
			$formatters = new ArrayObject([]);
			foreach (self::$_formatters as $fk => $fv) {
				$formatters->key($fk, new ArrayObject($fv));
			}
			self::$_formatters = $formatters;
		}
	}

	/**
	 * Returns format for defined $dateElement.
	 *
	 * @param string $dateElement Possible values are: date, year, month, day, time, hour, minutes, seconds, meridiem.
	 *
	 * @return mixed
	 */
	private function _getFormatFor($dateElement) {
		if($this->_dateTimeFormat->keyExists($dateElement)) {
			return $this->_dateTimeFormat->key($dateElement);
		}

		return self::$_formatters->key($dateElement)->first();
	}

	/**
	 * Checks if $format is a valid format for $dateElement.
	 *
	 * @param string $dateElement Possible values are: date, year, month, day, time, hour, minutes, seconds, meridiem.
	 * @param string $format      For list of possible formats check: http://php.net/manual/en/function.date.php
	 *
	 * @return mixed
	 * @throws StdObjectException
	 */
	private function _validateFormatFor($dateElement, $format) {
		if(!self::$_formatters->key($dateElement)->inArray($format)) {
			throw new StdObjectException('DateTimeObject: Invalid format "' . $format . '" for "get' . ucfirst($dateElement) . '"');
		}

		return $format;
	}

	/**
	 * Returns defined $dateElement in defined $format.
	 *
	 * @param string      $dateElement Possible values are: date, year, month, day, time, hour, minutes, seconds, meridiem.
	 * @param null|string $format      For list of possible formats check: http://php.net/manual/en/function.date.php
	 *
	 * @return string
	 */
	private function _getDateElement($dateElement, $format = null) {
		$format = ($this->isNull($format)) ? $this->_getFormatFor($dateElement) : $this->_validateFormatFor($dateElement,
																											$format);

		return $this->_getDateObject()->format($format);
	}

}