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
	 *
	 * @param Record $record A record to format
	 *
	 * @return mixed The formatted record
	 */
	public function formatRecord(Record $record);

	/**
	 * Formats multiple log records.
	 *
	 * @param  array $records A set of records to format
	 * @return mixed The formatted set of records
	 */
	public function formatRecords(array $records);
}
