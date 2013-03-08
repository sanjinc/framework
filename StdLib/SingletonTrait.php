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
 * Description
 *
 * @package         WebinyFramework
 * @category		StdLib
 */

trait SingletonTrait
{
	protected static $_wfInstance;

	/**
	 * Singleton trait.
	 * NOTE: This function must be declared static.
	 * This function must return:
	 * self::_getInstance();
	 *
	 * @return $this
	 */
	abstract function getInstance();

	/**
	 * @return self;
	 */
	final private static function _getInstance()
	{
		return isset(static::$_wfInstance) ? static::$_wfInstance : static::$_wfInstance = new static;
	}

	public function __construct()
	{
		#$this->exception('You cannot create a new instance of this object. Please use the getInstance static call.');
	}

	final private function __wakeup() {}
	final private function __clone() {}
}