<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Logger\Formatters;

use Webiny\Component\Logger\Record;
use Webiny\Component\StdLib\StdLibTrait;
use Webiny\WebinyTrait;

/**
 * Base Formatter class providing the Handler structure
 * @package Webiny\Component\Logger\Formatters
 */
abstract class FormatterAbstract implements FormatterInterface
{
	use StdLibTrait, WebinyTrait;

	protected $_config = null;

	/**
	 * Normalize record values, convert objects and resources to string representation, encode arrays to json, etc.
	 */
	public function normalizeValues(Record $record) {
		foreach ($record as $key => $value) {
			$record->$key = $this->_normalizeValue($value);
		}

		return $record;
	}

	private function _normalizeValue($data) {
		if($this->isNull($data) || $this->isScalar($data)) {
			return $data;
		}

		if($this->isStdObject($data)) {
			if($this->isDateTimeObject($data)) {
				if($this->isNull($this->_config->date_format)) {
					$format = $this->webiny()->getConfig()->components->logger->formatters->default->date_format;
				} else {
					$format = $this->_config->date_format;
				}
				return $data->format($format);
			}
			$data = $data->val();
		}

		if($this->isString($data)){
			return $data;
		}

		if($this->isArray($data) || $data instanceof \Traversable) {
			$normalized = array();
			foreach ($data as $key => $value) {
				$normalized[$key] = $this->_normalizeValue($value);
			}

			return $normalized;
		}

		if($this->isObject($data)) {
			if(method_exists($data, '__toString')) {
				return '' . $data;
			}

			return sprintf("[object] (%s: %s)", get_class($data), $this->jsonEncode($data));
		}

		if($this->isResource($data)) {
			return '[resource]';
		}

		return '[unknown(' . gettype($data) . ')]';
	}
}
