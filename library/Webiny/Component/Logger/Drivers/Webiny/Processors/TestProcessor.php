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
use Webiny\Component\Config\Config;
use Webiny\Component\Registry\RegistryTrait;
use Webiny\StdLib\ValidatorTrait;


/**
 * Formats incoming records into a one-line string
 *
 * @package         Webiny\Component\Logger\Formatters
 */
class TestProcessor implements ProcessorInterface
{
	/**
	 * Processes a log record.
	 *
	 * @param Record $record A record to format
	 *
	 * @return Record The formatted record
	 */
	public function processRecord(Record $record) {
		$record->extra['config'] = Config::PHP(WF.'/Test/Configs/config.php');
		$record->extra['framework_dir'] = WF;
		return $record;
	}
}
