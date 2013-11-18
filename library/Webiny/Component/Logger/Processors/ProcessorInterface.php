<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */
namespace Webiny\Component\Logger\Processors;

use Webiny\Component\Logger\Record;
use Webiny\Component\StdLib\StdLibTrait;

/**
 * Interface for processors
 * @package Webiny\Component\Logger\Processors
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
