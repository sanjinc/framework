<?php

namespace Webiny\Component\Logger;

use Webiny\Bridge\Logger\LoggerAbstract;
use Webiny\Bridge\Logger\LoggerDriverInterface;
use Webiny\Bridge\Logger\LoggerException;
use Webiny\Component\Logger\Drivers\Webiny;
use Webiny\StdLib\StdLibTrait;

/**
 * Webiny Logger
 *
 */
class Logger
{
	use StdLibTrait;

	/**
	 * @var null
	 */
	private $_driverInstance = null;

	/**
	 * Create new logger using given name and driver.<br>
	 * A name is used to identify log messages from different loggers.<br><br>
	 * Having two loggers ("Payment Gateway" and "Invoice Payment") would result in the following output:<br>
	 * <br>
	 * [Payment Gateway][info] Request sent.<br>
	 * [Invoice Payment][alert] Paid invoice amount is too small.
	 *
	 *
	 * @param string                $name Logger name
	 * @param LoggerDriverInterface $driverInstance
	 *
	 * @return \Webiny\Component\Logger\Logger
	 */
	public function __construct($name, $driverInstance) {
		$this->_driverInstance = $driverInstance;
		$this->_driverInstance->setName($name);
	}

	/**
	 * Get Webiny logger
	 *
	 * @param $name
	 *
	 * @return Webiny Webiny logger instance
	 */
	public static function Webiny($name) {
		return new static($name, new Webiny());
	}

	/**
	 * Call a method on driver instance
	 * Since we are wrapping an actual driver instance with this Logger object, we need a way to implement this magic method to forward the call to driver instance
	 *
	 * @param $name
	 * @param $arguments
	 *
	 * @return mixed
	 * @throws LoggerException
	 */
	function __call($name, $arguments) {
		if(method_exists($this->_driverInstance, $name)) {
			return call_user_func_array([
										$this->_driverInstance,
										$name
										], $arguments);
		}
		throw new LoggerException('Call to undefined method "' . $name . '" on Logger object! Make sure your logger driver provides the required method!');
	}


	/**
	 * System is unusable.
	 *
	 * @param string $message
	 * @param array  $context
	 *
	 * @return null
	 */
	public function emergency($message, array $context = array()) {
		$this->_driverInstance->emergency($message, $context);
	}

	/**
	 * Action must be taken immediately.
	 *
	 * Example: Entire website down, database unavailable, etc. This should
	 * trigger the SMS alerts and wake you up.
	 *
	 * @param string $message
	 * @param array  $context
	 *
	 * @return null
	 */
	public function alert($message, array $context = array()) {
		$this->_driverInstance->alert($message, $context);
	}

	/**
	 * Critical conditions.
	 *
	 * Example: Application component unavailable, unexpected exception.
	 *
	 * @param string $message
	 * @param array  $context
	 *
	 * @return null
	 */
	public function critical($message, array $context = array()) {
		$this->_driverInstance->critical($message, $context);
	}

	/**
	 * Runtime errors that do not require immediate action but should typically
	 * be logged and monitored.
	 *
	 * @param string $message
	 * @param array  $context
	 *
	 * @return null
	 */
	public function error($message, array $context = array()) {
		$this->_driverInstance->error($message, $context);
	}

	/**
	 * Exceptional occurrences that are not errors.
	 *
	 * Example: Use of deprecated APIs, poor use of an API, undesirable things
	 * that are not necessarily wrong.
	 *
	 * @param string $message
	 * @param array  $context
	 *
	 * @return null
	 */
	public function warning($message, array $context = array()) {
		$this->_driverInstance->warning($message, $context);
	}

	/**
	 * Normal but significant events.
	 *
	 * @param string $message
	 * @param array  $context
	 *
	 * @return null
	 */
	public function notice($message, array $context = array()) {
		$this->_driverInstance->notice($message, $context);
	}

	/**
	 * Interesting events.
	 *
	 * Example: User logs in, SQL logs.
	 *
	 * @param string $message
	 * @param array  $context
	 *
	 * @return null
	 */
	public function info($message, array $context = array()) {
		$this->_driverInstance->info($message, $context);
	}

	/**
	 * Detailed debug information.
	 *
	 * @param string $message
	 * @param array  $context
	 *
	 * @return null
	 */
	public function debug($message, array $context = array()) {
		$this->_driverInstance->debug($message, $context);
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param mixed  $level
	 * @param string $message
	 * @param array  $context
	 *
	 * @return null
	 */
	public function log($level, $message, array $context = array()) {
		$this->_driverInstance->log($level, $message, $context);
	}
}