<?php

namespace Webiny\Bridge\Logger\Webiny;

use Webiny\Component\Registry\RegistryTrait;
use Webiny\StdLib\StdLibTrait;

/**
 * Base Formatter class providing the Handler structure
 */
abstract class FormatterAbstract implements FormatterInterface
{
	use StdLibTrait, RegistryTrait;

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
		if($this->isNull($data) || is_scalar($data)) {
			return $data;
		}

		if($this->isStdObject($data)) {
			if($this->isDateTimeObject($data)) {
				if($this->isNull($this->_config->date_format)) {
					$format = $this->registry()->webiny->components->logger->formatters->default->date_format;
				} else {
					$format = $this->_config->date_format;
				}
				return $data->format($format);
			}
			$data = $data->val();
		}

		if(is_array($data) || $data instanceof \Traversable) {
			$normalized = array();
			foreach ($data as $key => $value) {
				$normalized[$key] = $this->_normalizeValue($value);
			}

			return $normalized;
		}

		if(is_object($data)) {
			if(method_exists($data, '__toString')) {
				return '' . $data;
			}

			return sprintf("[object] (%s: %s)", get_class($data), json_encode($data));
		}

		if(is_resource($data)) {
			return '[resource]';
		}

		return '[unknown(' . gettype($data) . ')]';
	}
}
