<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */
namespace Webiny\Bridge\Logger\Webiny;

use Webiny\Bridge\Logger\LoggerException;
use Webiny\StdLib\StdLibTrait;

/**
 * Interface for processors
 * @package Webiny\Bridge\Logger\Webiny
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
