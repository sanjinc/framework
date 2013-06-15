<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Logger\Drivers\Webiny\Processors;

use Webiny\Bridge\Logger\Webiny\ProcessorInterface;
use Webiny\Bridge\Logger\Webiny\Record;

/**
 * FileLineProcessor adds 'file' and 'line' values to the Record 'extra' data
 * 
 * @package Webiny\Component\Logger\Drivers\Webiny\Processors
 */
class FileLineProcessor implements ProcessorInterface
{

	/**
	 * Processes a log record.
	 *
	 * @param Record $record A record to format
	 *
	 * @return Record The formatted record
	 */
	public function processRecord(Record $record) {
		
		$backtrace = debug_backtrace();
		$backtrace = $backtrace[5];

		$record->extra['file'] = $backtrace['file'];
		$record->extra['line'] = $backtrace['line'];
	}
}