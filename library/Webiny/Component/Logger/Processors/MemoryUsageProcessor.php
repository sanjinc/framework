<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Logger\Processors;

use Webiny\Component\Logger\Processors\ProcessorInterface;
use Webiny\Component\Logger\Record;

/**
 * MemoryUsageProcessor adds 'memory_usage' (current allocated amount of memory) to the Record 'extra' data
 * 
 * @package Webiny\Component\Logger\Processors
 */
class MemoryUsageProcessor implements ProcessorInterface
{

	/**
	 * Processes a log record.
	 *
	 * @param Record $record A record to format
	 *
	 * @return Record The formatted record
	 */
	public function processRecord(Record $record) {
		$record->extra['memory_usage'] = memory_get_usage(true);
	}
}