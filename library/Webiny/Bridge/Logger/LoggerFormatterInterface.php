<?php

namespace Webiny\Bridge\Logger;

/**
 * Interface for formatters
 *
 */
interface LoggerFormatterInterface
{
	/**
	 * Formats a log record.
	 *
	 * @param  array $record A record to format
	 * @return mixed The formatted record
	 */
	public function formatRecord(array $record);

	/**
	 * Formats multiple log records.
	 *
	 * @param  array $records A set of records to format
	 * @return mixed The formatted set of records
	 */
	public function formatRecords(array $records);
}
