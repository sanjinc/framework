<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace WF\StdLib\StdObject\DateObject;

use WF\StdLib\Config\ConfigAbstract;
use WF\StdLib\Config\ConfigTrait;
use WF\StdLib\StdObject\StringObject\StringObject;

/**
 * DateObject config.
 *
 * @package         WF\StdLib\StdObject\DateObject
 */
class DateObjectConfig extends ConfigAbstract
{
	protected $_configKey = __CLASS__;

	/**
	 * Construct
	 *
	 * @param bool $overwriteDefault
	 */
	public function __construct($overwriteDefault = false) {
		// construct the Config object
		parent::__construct($overwriteDefault);

		// set default config
		$this->_setDefaultConfig([
								 'timezone' => date_default_timezone_get(),
								 'format'   => 'Y-m-d H:i:s'
								 ]);
	}

	/**
	 * Set date output format.
	 *
	 * @param null|string $format List of available formats can be found under this link:
	 *                            http://www.php.net/manual/en/function.date.php
	 *
	 * @return DateObjectConfig|StringObject|mixed
	 */
	public function format($format = null) {
		return $this->_config('format', $format);
	}

	/**
	 * Set current timezone.
	 * Default timezone is the server timezone.
	 *
	 * @param null|string $timezone List of available timezones: http://www.php.net/manual/en/timezones.php
	 *
	 * @return DateObjectConfig|StringObject|mixed
	 */
	public function timezone($timezone = null) {
		return $this->_config('timezone', $timezone);
	}
}