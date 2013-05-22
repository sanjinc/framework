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
 * Formats incoming records into a one-line string
 *
 * @package         Webiny\Component\Logger\Formatters
 */
class FileFormatter extends FormatterAbstract
{
	protected $_format;

	protected $_dateFormat;

	/**
	 * @param string $format     The format of the message
	 * @param string $dateFormat The format of the timestamp: one supported by DateTime::format
	 */
	public function __construct($format = null, $dateFormat = null) {
		$this->_config = $this->registry()->webiny->components->logger->formatters->file;
		if($this->isNull($format)) {
			$format = str_replace('\n', "\n", $this->_config->record_format);
		}

		$this->_format = $format;
		$this->dateFormat = $dateFormat !== null ? $dateFormat : $this->_config->date_format;
	}

	public function formatRecord(Record $record) {

		// Call this to execute standard value normalization
		$record = $this->normalizeValues($record);

		$output = $this->str($this->_format);

		// Handle extra values in case specific values are given in record format
		foreach ($record->extra as $var => $val) {
			if($output->contains('%extra.' . $var . '%')) {
				$output->replace('%extra.' . $var . '%', $val);
				unset($record->extra[$var]);
			}
		}

		// Handle main record values
		foreach ($record as $var => $val) {
			if($this->isDateTimeObject($val)) {
				$val = $val->format($this->dateFormat);
			}
			if(is_object($val)) {
				if(method_exists($val, '__toString')) {
					$val = '' . $val;
				}
			} elseif(is_array($val)) {
				$val = json_encode($val);
			}
			$output->replace('%' . $var . '%', $val);
		}

		return $output->val();
	}

	public function formatRecords(array $records) {
		$message = '';
		foreach ($records as $record) {
			$message .= $this->formatRecord($record);
		}

		return $message;
	}
}
