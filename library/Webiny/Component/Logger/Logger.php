<?php

namespace Webiny\Component\Logger;

use Psr\Log\InvalidArgumentException;
use Webiny\Bridge\Logger\LoggerAbstract;
use Webiny\StdLib\StdLibTrait;
use Webiny\StdLib\StdObject\StdObjectWrapper;

/**
 * Webiny Logger
 *
 */
class Logger
{
	use StdLibTrait;

	const DRIVER = '\Webiny\Component\Logger\Drivers\Monolog';

	private static $_driverClass = '\Webiny\Bridge\Logger\LoggerAbstract';

	/**
	 * @var null|LoggerAbstract
	 */
	private $_loggerInstance = null;

	/**
	 * @param        $channelName
	 * @param string $driverClass
	 *
	 * @throws LoggerException
	 * @return \Webiny\Component\Logger\Logger
	 */
	public function __construct($channelName, $driverClass = Logger::DRIVER) {
		// Validate channel name
		if(!self::isString($channelName) && !self::isStringObject($channelName)) {
			throw new LoggerException(LoggerException::MSG_INVALID_ARG, [
																		'$channelName',
																		'string or StringObject'
																		]);
		}
		$channelName = StdObjectWrapper::toString($channelName);

		// Validate driver class
		if(!self::isString($driverClass) && !self::isStringObject($driverClass)) {
			throw new LoggerException(LoggerException::MSG_INVALID_ARG, [
																		'$driverClass',
																		'string or StringObject'
																		]);
		}

		if(!class_exists($driverClass)) {
			throw new LoggerException("Provided Logger driver class '" . $driverClass . "' does not exist!");
		}

		// Get instance of logger
		$this->_loggerInstance = $driverClass::getInstance($channelName);

		// Validate logger
		if(!self::isInstanceof($this->_loggerInstance, self::$_driverClass)) {
			throw new LoggerException('Logger driver must be an instance of ' . self::$_driverClass);
		}
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
	 * @param mixed $level
	 * @param string $message
	 * @param array $context
	 *
	 * @return null
	 */
	public function log($level, $message, array $context = array()) {
		$this->_loggerInstance->log($level, $message, $context);
	}

	public function addHandler($handler){
		$this->_loggerInstance->addHandler($handler);
	}
}
