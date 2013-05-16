<?php

namespace Webiny\Bridge\Logger;

interface LoggerHandlerInterface
{
	/**
	 * Checks whether the given record will be handled by this handler.
	 *
	 * This is mostly done for performance reasons, to avoid calling processors for nothing.
	 *
	 * Handlers should still check the record levels within handle(), returning false in isHandling()
	 * is no guarantee that handle() will not be called, and isHandling() might not be called
	 * for a given record.
	 *
	 * @param array $record
	 *
	 * @return Boolean
	 */
	public function isHandling(array $record);

	/**
	 * Handles a record.
	 *
	 * All records may be passed to this method, and the handler should discard
	 * those that it does not want to handle.
	 *
	 * The return value of this function controls the bubbling process of the handler stack.
	 * Unless the bubbling is interrupted (by returning true), the Logger class will keep on
	 * calling further handlers in the stack with a given log record.
	 *
	 * @param  array   $record The record to handle
	 * @return Boolean True means that this handler handled the record, and that bubbling is not permitted.
	 *                 False means the record was either not processed or that this handler allows bubbling.
	 */
	public function processRecord(array $record);

	/**
	 * Handles a set of records at once.
	 *
	 * @param array $records The records to handle (an array of record arrays)
	 */
	public function processRecords(array $records);

	/**
	 * Adds a processor in the stack.
	 *
	 * @param callable $callback
	 */
	public function addProcessor($callback);

	/**
	 * Sets the formatter.
	 *
	 * @param FormatterInterface $formatter
	 */
	public function setFormatter(FormatterInterface $formatter);

}
