<?php

namespace Webiny\Component\Logger;

use Webiny\Bridge\Logger\LoggerAbstract;
use Webiny\Bridge\Logger\LoggerDriverInterface;
use Webiny\Bridge\Logger\LoggerException;

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
	 * @param LoggerDriverInterface $driverInstance
	 *
	 * @return \Webiny\Component\Logger\Logger
	 */
	public function __construct($driverInstance) {
		$this->_driverInstance = $driverInstance;
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
		$this->_loggerInstance->emergency($message, $context);
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
		$this->_loggerInstance->alert($message, $context);
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
		$this->_loggerInstance->critical($message, $context);
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
		$this->_loggerInstance->error($message, $context);
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
		$this->_loggerInstance->warning($message, $context);
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
		$this->_loggerInstance->notice($message, $context);
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
		$this->_loggerInstance->info($message, $context);
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
		$this->_loggerInstance->debug($message, $context);
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
		$this->_loggerInstance->log($level, $message, $context);
	}
}