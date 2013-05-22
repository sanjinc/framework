<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Logger\Drivers\Webiny\Handlers;

use Webiny\Bridge\Logger\LoggerAbstract;
use Webiny\Bridge\Logger\LoggerHandlerAbstract;
use Webiny\Bridge\Logger\Webiny\HandlerAbstract;
use Webiny\Bridge\Logger\Webiny\Record;
use Webiny\Component\Logger\Drivers\Webiny\Formatters\FileFormatter;
use Webiny\Component\Logger\LoggerException;
use Webiny\StdLib\StdLibTrait;
use Webiny\StdLib\StdObject\StdObjectException;

/**
 * @package         Webiny\Component\Logger\Handlers
 */
class FileHandler extends HandlerAbstract
{
	use StdLibTrait;

	private $_file;

	public function __construct($file, $levels = [], $bubble = true, $buffer = false){
		parent::__construct($levels, $bubble, $buffer);
		try{
			$this->_file = $this->file($file);
		} catch(StdObjectException $e){
			throw new LoggerException($e->getMessage());
		}

	}


	/**
	 * Writes the record down to the log of the implementing handler
	 *
	 * @param Record $record
	 *
	 * @return void
	 */
	protected function write(Record $record) {
		$this->_file->write($record->formatted);
	}

	protected function _getDefaultFormatter() {
		return new FileFormatter();
	}
}