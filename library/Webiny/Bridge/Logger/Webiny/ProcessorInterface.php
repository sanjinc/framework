<?php

namespace Webiny\Bridge\Logger\Webiny;

/**
 * Interface for processors
 *
 */
interface ProcessorInterface
{
	/**
	 * Processes a log record.
	 *
	 * @param Record $record A record to format
	 *
	 * @return Record The formatted record
	 */
	public function processRecord(Record $record);

}
