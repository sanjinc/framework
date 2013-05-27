<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Logger\Drivers\Webiny\Formatters;

use Webiny\Bridge\Logger\Webiny\FormatterAbstract;
use Webiny\Bridge\Logger\Webiny\Record;


/**
 * Formats incoming records into a request for Webiny Tray Notifier
 *
 * @package         Webiny\Component\Logger\Formatters
 */
class WebinyTrayFormatter extends FormatterAbstract
{
	/**
	 * Message date format
	 * @var string
	 */
	protected $_dateFormat;

	/**
	 * JsonRPC method to call
	 * @var string
	 */
	private $_method;

	/**
	 * @param string|null $method     JsonRPC method to call
	 * @param string|null $dateFormat The format of the timestamp: one supported by DateTime::format
	 */
	public function __construct($method = null, $dateFormat = null) {
		$this->_config = $this->webiny()->getConfig()->components->logger->formatters->webiny_tray;
		$this->_dateFormat = $dateFormat !== null ? $dateFormat : $this->_config->date_format;
		$this->_method = $method !== null ? $method : $this->_config->method;
	}

	public function formatRecord(Record $record) {

		// Call this to execute standard value normalization
		$record = $this->normalizeValues($record);

		$output = [];

		// Handle main record values
		foreach ($record as $var => $val) {
			if($this->isDateTimeObject($val)) {
				$val = $val->format($this->dateFormat);
			}
			if($this->isObject($val)) {
				$val = (array)$val;
			} elseif($this->isArray($val)) {
				$val = json_encode($val);
			}

			$output['params'][$var] = $val;
		}

		return $output;
	}

	public function formatRecords(array $records, Record $record) {
		$request = [];
		foreach ($records as $record) {
			$request['messages'][] = $this->formatRecord($record);
		}

		$json = [
			'jsonrpc' => '2.0',
			'method'  => $this->_method,
			'params'  => $request
		];

		$record->formatted = $json;
	}
}


/*$tray = $this->registry()->webiny->components->logger->tray;

if($this->isInstanceOf($tray, 'Webiny\Component\Config\ConfigObject')) {
	$tray = $tray->toArray();
}

if(!$this->isArray($tray) && !$this->isArrayObject($tray)) {
	$tray = [$tray];
}*/