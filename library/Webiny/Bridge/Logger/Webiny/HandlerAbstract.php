<?php

namespace Webiny\Bridge\Logger\Webiny;

use Webiny\Bridge\Logger\LoggerException;
use Webiny\StdLib\StdLibTrait;
use Webiny\Bridge\Logger\Webiny\Record;

/**
 * Base Handler class providing the Handler structure
 */
abstract class HandlerAbstract
{
	use StdLibTrait;

	protected $_levels = [];
	protected $_bubble = true;
	protected $_buffer = false;

	/**
	 * @var FormatterInterface
	 */
	protected $_formatter = null;
	protected $_processors = [];

	/**
	 * Writes the record down to the log of the implementing handler
	 *
	 * @param Record $record
	 *
	 * @return void
	 */
	abstract protected function write(Record $record);

	abstract protected function _getDefaultFormatter();

	/**
	 * @param array|ArrayObject $levels  The minimum logging level at which this handler will be triggered
	 * @param Boolean           $bubble  Whether the messages that are handled can bubble up the stack or not
	 * @param bool              $buffer
	 */
	public function __construct($levels = [], $bubble = true, $buffer = false) {

		$this->_levels = $this->arr($levels);
		$this->_bubble = $bubble;
		$this->_buffer = $buffer;
		$this->_processors = $this->arr();
	}

	public function __destruct() {
		try {
			$this->stopHandling();
		} catch (\Exception $e) {
			// do nothing
		}
	}

	public function canHandle(Record $record) {
		if($this->_levels->count() < 1) {
			return true;
		}

		return $this->_levels->inArray($record->level);
	}

	/**
	 * Stop handling
	 *
	 * This will be called automatically when the object is destroyed
	 */
	public function stopHandling() {

	}

	public function addProcessor($callback) {
		if(!is_callable($callback) && !$this->isInstanceOf($callback,
														   '\Webiny\Bridge\Logger\Webiny\ProcessorInterface')
		) {
			throw new \InvalidArgumentException('Processor must be valid callable or an instance of \Webiny\Bridge\Logger\Webiny\ProcessorInterface');
		}
		$this->_processors->prepend($callback);
		return $this;
	}

	public function setFormatter(FormatterInterface $formatter) {
		$this->_formatter = $formatter;
		return $this;
	}

	public function process(Record $record) {
		$record = $this->processRecord($record);

		$record->formatted = $this->_getFormatter()->formatRecord($record);

		$this->write($record);

		return $this->_bubble;
	}

	/**
	 * Processes a record.
	 *
	 * @param Record $record
	 *
	 * @return Record
	 */
	public function processRecord(Record $record) {
		if($this->_processors) {
			foreach ($this->_processors as $processor) {
				if($this->isInstanceOf($processor, '\Webiny\Bridge\Logger\Webiny\ProcessorInterface')){
					$record = $processor->processRecord($record);
				} else {
					$record = call_user_func($processor, $record);
				}

			}
		}

		return $record;
	}

	public function processRecords(array $records) {
		foreach ($records as $record) {
			$this->processRecord($record);
		}
	}

	/**
	 * @throws \Webiny\Bridge\Logger\LoggerException
	 * @return FormatterInterface
	 */
	private function _getFormatter() {
		if($this->isNull($this->_formatter)) {
			$this->_formatter = $this->_getDefaultFormatter();
			if(!$this->isInstanceOf($this->_formatter, '\Webiny\Bridge\Logger\Webiny\FormatterInterface')) {
				throw new LoggerException('Formatter must be an instance of \Webiny\Bridge\Logger\Webiny\FormatterInterface');
			}

		}

		return $this->_formatter;
	}
}
