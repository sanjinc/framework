<?php

namespace Webiny\Bridge\Logger\Webiny;

/**
 * Interface for formatters
 *
 */
interface FormatterInterface
{
	/**
	 * Formats a log record.
	 * Change Record object as you see fit.
	 *
	 * Assign formatted value to: $record->formatted
	 *
	 * @param Record $record A record to format
	 */
	public function formatRecord(Record $record);

	/**
	 * Formats multiple log records
	 * The second parameter contains the Record object that will be passed to HandlerAbstract->write($record) method.
	 * Modify the Record object as you see fit.
	 *
	 * Assign formatted value to: $record->formatted
	 *
	 * @param  array $records A set of records to format
	 * @param Record $record  A final record object
	 */
	public function formatRecords(array $records, Record $record);
}
