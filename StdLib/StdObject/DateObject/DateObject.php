<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace WF\StdLib\StdObject\DateObject;

use WF\StdLib\StdObject\StdObjectAbstract;
use WF\StdLib\StdObject\StdObjectException;
use WF\StdLib\ValidatorTrait;

/**
 * Date standard object.
 * Class that enables you to work with dates much easier.
 *
 * @package         WF\StdLib\StdObject\DateObject
 */
class DateObject extends StdObjectAbstract
{
	use ValidatorTrait;

	/**
	 * This date is always in UTC timezone.
	 * The entry date is automatically converted from entry timezone to UTC.
	 *
	 * @var \DateTime|null
	 */
	protected $_value = null;

	/**
	 * Offset in seconds between current timezone and UTC.
	 * @var int
	 */
	private $_offset = 0;


	/**
	 * Constructor.
	 * Set standard object value.
	 *
	 * @param string|int $time     A date/time string. List of available formats is explained here
	 *                             http://www.php.net/manual/en/datetime.formats.php
	 * @param int|string $timeZone Time zone in which the $time is represented. A list of available time zones can be
	 *                             found here http://www.php.net/manual/en/timezones.php
	 *
	 * @throws StdObjectException
	 * @internal param mixed $value
	 */
	function __construct($time = "now", $timeZone = null) {
		try {
			$this->_entryTimeZone = $this->_createTimezone($timeZone);
			$this->_value = new \DateTime($time, $this->_entryTimeZone);

			// get UTC offset and correct the date to UTC by calculating the offset
			$this->_offsetToUTC();
		} catch (\Exception $e) {
			throw new StdObjectException('DateObject: Unable to create a DateObject.', 0, $e);
		}
	}

	/**
	 * Get DateObjectConfig.
	 * IMPORTANT: This returns the config params for current instance. You can use DateObject::defaultConfig if you
	 * want to get, or change, the default config for this object.
	 *
	 * @return null|DateObjectConfig
	 */
	public function config(){
		return $this->_getConfig();
	}

	/**
	 * Returns default config for current standard object.
	 *
	 * @return null|DateObjectConfig
	 */
	static public function defaultConfig(){
		return self::_getDefaultConfig();
	}

	/**
	 * To string implementation.
	 *
	 * @return mixed
	 */
	function __toString() {
		// offset from UTC to user-defined timezone
		if($this->_entryTimeZone->getName()!='UTC'){
			if($this->_offset>0){
				$this->_value->add(new \DateInterval('PT'.$this->_offset.'S'));
			}else if($this->_offset<0){
				$this->_value->sub(new \DateInterval('PT'.$this->_offset.'S'));
			}
		}
		$date = $this->_value->format($this->config()->format());

		// once we have the date in the right timezone, revert back to utc
		$this->_offsetToUTC();

		return $date;
	}

	/**
	 * Create a DateTimeZone object for the given $timeZone.
	 *
	 * @param string $timezone A valid time zone. For list of available timezones visit:
	 *                         http://www.php.net/manual/en/timezones.php
	 *
	 * @return \DateTimeZone
	 * @throws StdObjectException
	 */
	private function _createTimezone($timezone) {
		try {
			if($this->isNull($timezone))
			{
				$timezone = $this->config()->timezone()->val();
			}
			$timezone = new \DateTimeZone($timezone);
		} catch (\Exception $e) {
			throw new StdObjectException('DateObject: Unable to create a valid time zone for given zone: ' . $timezone, 0, $e);
		}

		return $timezone;
	}

	/**
	 * Offsets the date object from current timezone to UTC
	 */
	private function _offsetToUTC(){
		if($this->_entryTimeZone->getName()!='UTC'){
			if($this->_offset>0){
				$this->_value->sub(new \DateInterval('PT'.$this->_offset.'S'));
			}else if($this->_offset<0){
				$this->_value->add(new \DateInterval('PT'.$this->_offset.'S'));
			}
		}
	}

}