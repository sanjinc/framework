<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Logger\Drivers;

use Webiny\Bridge\Logger\LoggerAbstract;
use Webiny\Bridge\Logger\LoggerDriverInterface;
use Webiny\Bridge\Logger\LoggerHandlerAbstract;
use Webiny\Bridge\Logger\LoggerLevel;
use Webiny\Bridge\Logger\Webiny\HandlerAbstract;
use Webiny\Bridge\Logger\Webiny\Record;
use Webiny\Component\Logger\LoggerException;
use Webiny\StdLib\StdLibTrait;
use Webiny\StdLib\StdObject\ArrayObject\ArrayObject;

/**
 * Webiny logger driver covers most of your logging needs.<br />
 * The way the message is output is controlled through handlers and formatters so you can use this driver in most cases.
 *
 * @package         Webiny\Component\Logger\Drivers
 */
class Webiny implements LoggerDriverInterface
{
	use StdLibTrait;

	/**
	 * Name of the logger which will appear in your log records
	 * @var string
	 */
	private $_name;

	/**
	 * Handlers to use when logging a message
	 * @var ArrayObject
	 */
	private $_handlers;

	function __construct(){
		$this->_handlers = $this->arr();
	}

	/**
	 * Set logger name
	 *
	 * @param string $name Logger name
	 */
	public function setName($name){
		$this->_name = $name;
	}

	/**
	 * Add handler to logger
	 * Handlers are being prepended to the handlers array, so the last added handler will be executed first
	 * @param HandlerAbstract $handler
	 */
	public function addHandler(HandlerAbstract $handler){
		$this->_handlers->prepend($handler);
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
		$this->_addRecord(LoggerLevel::EMERGENCY, $message, $context);
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
		$this->_addRecord(LoggerLevel::ALERT, $message, $context);
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
		$this->_addRecord(LoggerLevel::CRITICAL, $message, $context);
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
		$this->_addRecord(LoggerLevel::ERROR, $message, $context);
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
		$this->_addRecord(LoggerLevel::WARNING, $message, $context);
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
		$this->_addRecord(LoggerLevel::NOTICE, $message, $context);
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
		$this->_addRecord(LoggerLevel::INFO, $message, $context);
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
		$this->_addRecord(LoggerLevel::DEBUG, $message, $context);
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
		$this->_addRecord($level, $message, $context);
	}

	/**
	 * Adds a log record.
	 *
	 * @param  integer $level   The logging level
	 * @param  string  $message The log message
	 * @param  array   $context The log context
	 *
	 * @throws LoggerException
	 * @return Boolean Whether the record has been processed
	 */
	protected function _addRecord($level, $message, array $context = array())
	{
		if ($this->_handlers->count() < 1) {
			throw new LoggerException('To log a record you must add at least one HandlerAbstract object to handle the messages.');
		}

		$record = new Record();
		$record->message = (string) $message;
		$record->context = $context;
		$record->level = $level;
		$record->name = $this->_name;
		$record->datetime = $this->datetime("now");
		$record->extra = [];

		// check if any handler will handle this message
		$canHandle = false;
		foreach ($this->_handlers as $handler) {
			if ($handler->canHandle($record)) {
				$canHandle = true;
				break;
			}
		}
		// none found
		if (!$canHandle) {
			return false;
		}

		/* @var $handler \Webiny\Bridge\Logger\Webiny\HandlerAbstract */
		foreach($this->_handlers as $handler) {
			if($handler->canHandle($record)){
				$bubble = $handler->process(clone $record);
				if(!$bubble){
					break;
				}
			}
		}
		return true;
	}

}