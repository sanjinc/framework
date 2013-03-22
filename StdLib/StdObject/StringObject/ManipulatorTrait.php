<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 * @package   WebinyFramework
 */

namespace WF\StdLib\StdObject\StringObject;

/**
 * String manipulators.
 *
 * @package         WebinyFramework
 * @category        StdLib
 * @subcategory        String
 */

trait ManipulatorTrait
{
	use \WF\StdLib\StdObject\StdObjectManipulatorTrait;

	abstract function getValue();

	/**
	 * @return StringObject
	 */
	abstract function getObject();

	/**
	 * Strip whitespace (or other characters) from the beginning and end of a string.
	 *
	 * @param string|null $char - char you want to trim
	 *
	 * @return StringObject
	 */
	public function trim($char = null) {
		$value = trim($this->getValue(), $char);
		$this->getObject()->updateValue($value);

		return $this;
	}

	/**
	 * Make a string lowercase.
	 *
	 * @return StringObject
	 */
	public function lower() {
		$value = strtolower($this->getValue());
		$this->getObject()->updateValue($value);

		return $this;
	}

	/**
	 * Make a string uppercase.
	 *
	 * @return StringObject
	 */
	public function upper() {
		$value = strtoupper($this->getValue());
		$this->getObject()->updateValue($value);

		return $this;
	}

	/**
	 * Strips trailing slash from the current string.
	 *
	 * @return StringObject
	 */
	public function stripTrailingSlash() {
		$value = rtrim($this->getValue(), '/');
		$this->getObject()->updateValue($value);

		return $this;
	}

	/**
	 * Strips a slash from the start of the string.
	 *
	 * @return StringObject
	 */
	public function stripStartingSlash() {
		$value = ltrim($this->getValue(), '/');
		$this->getObject()->updateValue($value);

		return $this;
	}

	/**
	 * Returns a substring from the current string.
	 * NOTE: the returned substring is actually a new instance of StringObject.
	 *
	 * @param int $startPosition
	 * @param int $endPosition
	 *
	 * @return StringObject
	 */
	public function subString($startPosition, $endPosition) {
		$value = substr($this->getValue(), $startPosition, $endPosition);

		return new StringObject($value);
	}

	/**
	 * Replaces the $search inside the current value with $replace.
	 * NOTE: the returned substring is actually a new instance of StringObject.
	 *
	 * @param string|array $search
	 * @param string|array $replace
	 *
	 * @return StringObject
	 */
	public function replace($search, $replace) {
		$value = str_replace($search, $replace, $this->getValue());

		return new StringObject($value);
	}
}