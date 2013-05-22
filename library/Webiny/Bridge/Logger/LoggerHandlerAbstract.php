<?php

namespace Webiny\Bridge\Logger;

use Webiny\Component\Logger\Formatters\LineFormatter;

/**
 * Base Handler class providing the Handler structure
 *
 */
abstract class LoggerHandlerAbstract implements LoggerHandlerInterface
{
	protected $_levels = [];
	protected $_bubble = false;

	/**
	 * @var FormatterInterface
	 */
	protected $_formatter;
	protected $_processors = array();

	/**
	 * Writes the record down to the log of the implementing handler
	 *
	 * @param  array $record
	 * @return void
	 */
	abstract protected function write(array $record);

	/**
	 * @param array|ArrayObject $levels  The minimum logging level at which this handler will be triggered
	 * @param Boolean           $bubble Whether the messages that are handled can bubble up the stack or not
	 */
	public function __construct($levels = [], $bubble = true) {
		$this->_levels = $levels;
		$this->_bubble = $bubble;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isHandling(array $record) {
		if(empty($this->_levels)){
			return true;
		}
		return in_array($record['level'], $this->_levels);
	}

	/**
	 * {@inheritdoc}
	 */
	public function processRecords(array $records) {
		foreach ($records as $record) {
			$this->process($record);
		}
	}

	/**
	 * Closes the handler.
	 *
	 * This will be called automatically when the object is destroyed
	 */
	public function stopLogging() {
	}

	/**
	 * {@inheritdoc}
	 */
	public function addProcessor($callback) {
		if(!is_callable($callback)) {
			throw new \InvalidArgumentException('Processors must be valid callables (callback or object with an __invoke method), ' . var_export($callback,
																																				 true) . ' given');
		}
		array_unshift($this->_processors, $callback);
	}

	/**
	 * {@inheritdoc}
	 */
	public function setFormatter(FormatterInterface $formatter) {
		$this->_formatter = $formatter;
	}

	/**
	 * Sets the bubbling behavior.
	 *
	 * @param Boolean $bubble True means that bubbling is not permitted.
	 *                        False means that this handler allows bubbling.
	 */
	public function setBubble($bubble) {
		$this->_bubble = $bubble;
	}

	/**
	 * Gets the bubbling behavior.
	 *
	 * @return Boolean True means that bubbling is not permitted.
	 *                 False means that this handler allows bubbling.
	 */
	public function getBubble() {
		return $this->_bubble;
	}

	public function __destruct() {
		try {
			$this->stopLogging();
		} catch (\Exception $e) {
			// do nothing
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function process(array $record)
	{
		if (!in_array($record['level'], $this->_levels)) {
			return false;
		}

		$record = $this->processRecord($record);

		$record['formatted'] = $this->getFormatter()->format($record);

		$this->write($record);

		return false === $this->_bubble;
	}

	/**
	 * Processes a record.
	 *
	 * @param  array $record
	 * @return array
	 */
	public function processRecord(array $record)
	{
		if ($this->_processors) {
			foreach ($this->_processors as $processor) {
				$record = call_user_func($processor, $record);
			}
		}

		return $record;
	}

	/**
	 * Gets the default formatter.
	 *
	 * @return FormatterInterface
	 */
	protected function getDefaultFormatter() {
		return new LineFormatter();
	}
}
