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

use WF\StdLib\StdObject\ArrayObject\ArrayObject;
use WF\StdLib\StdObject\StdObjectAbstract;
use WF\StdLib\StdObject\StringObject\ManipulatorTrait;
use WF\StdLib\StdObject\StringObject\ValidatorTrait;

/**
 * String standard object.
 *
 * @package         WF\StdLib\StdObject\StringObject
 */

class StringObject extends StdObjectAbstract
{
	use ManipulatorTrait,
		ValidatorTrait;

	/**
	 * @var string
	 */
	protected $_wfString;


	/**
	 * Constructor.
	 * Set standard object value.
	 *
	 * @param mixed $value
	 */
	public function __construct($value) {
		$this->_wfString = (string)$value;
	}

	/**
	 * Returns the lenght of the current string.
	 *
	 * @return int
	 */
	public function length() {
		return strlen($this->getValue());
	}

	/**
	 * Return the number of words in the string.
	 *
	 * @param int $format Specify the return format:
	 * 0 - return number of words
	 * 1 - return an ArrayObject containing all the words found inside the string
	 * 2 - returns an ArrayObject, where the key is the numeric position of the word
	 *                    inside the string and the value is the actual word itself
	 *
	 * @return mixed|ArrayObject
	 */
	public function wordCount($format = 0) {
		if($format < 1) {
			return str_word_count($this->getValue(), $format);
		} else {
			return new ArrayObject(str_word_count($this->getValue(), $format));
		}

	}

	/**
	 * Return current standard objects value.
	 *
	 * @return string
	 */
	public function getValue() {
		return $this->_wfString;
	}

	/**
	 * To string implementation.
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->getValue();
	}

	/**
	 * Returns the current standard object instance.
	 * @return $this
	 */
	public function getObject() {
		return $this;
	}

	/**
	 * The update value method is called after each modifier method.
	 * It updates the current value of the standard object.
	 */
	public function updateValue($value) {
		$this->_wfString = $value;
	}

}