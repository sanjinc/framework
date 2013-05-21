<?php

namespace Webiny\Bridge\Logger\Webiny;

use Webiny\StdLib\StdLibTrait;

/**
 * Base Formatter class providing the Handler structure
 */
abstract class FormatterAbstract implements FormatterInterface
{
	use StdLibTrait;

	/**
	 * Normalize record values, convert objects and resources to string representation, encode arrays to json, etc.
	 */
	public function normalizeValues() {

		foreach ($this as $key => $value) {
			$this->$key = $this->_normalizeValue($value);
		}
	}

	private function toJson($data) {
		return @json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
	}

	private function _normalizeValue($data) {
		if(null === $data || is_scalar($data)) {
			return $data;
		}

		if($this->isStdObject($data)) {
			$data = $data->val();
		}

		if(is_array($data) || $data instanceof \Traversable) {
			$normalized = array();
			foreach ($data as $key => $value) {
				$normalized[$key] = $this->_normalizeValue($value);
			}

			return $this->toJson($normalized);
		}

		if(is_object($data)) {
			return sprintf("[object] (%s: %s)", get_class($data), $this->toJson($data, true));
		}

		if(is_resource($data)) {
			return '[resource]';
		}

		return '[unknown(' . gettype($data) . ')]';
	}
}
