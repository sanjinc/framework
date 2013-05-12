<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 * @package   WebinyFramework
 */

namespace Webiny\StdLib\StdObject\StringObject;

use Webiny\StdLib\StdObject\ArrayObject\ArrayObject;
use Webiny\StdLib\StdObject\StdObjectAbstract;
use Webiny\StdLib\StdObject\StringObject\ManipulatorTrait;
use Webiny\StdLib\StdObject\StringObject\ValidatorTrait;

/**
 * String standard object.
 * This is a helper class for working with strings.
 *
 * Example:
 * $s = new StringObject('some string');
 *
 * @package         Webiny\StdLib\StdObject\StringObject
 */

class StringObject extends StdObjectAbstract
{
	use ManipulatorTrait,
		ValidatorTrait;

	/**
	 * Default file encoding.
	 * Used by multibyte string functions.
	 */
	const DEF_ENCODING = 'UTF-8';

	/**
	 * @var string
	 */
	protected $_value;


	/**
	 * Constructor.
	 * Set standard object value.
	 *
	 * @param string|int $value A string from which the StringObject instance will be created.
	 *
	 * @throws StringObjectException
	 */
	public function __construct($value) {
		if(!$this->isString($value) && !$this->isNumber($value)) {
			if($this->isInstanceOf($value, $this)){
				return $value;
			}

			throw new StringObjectException(StringObjectException::MSG_INVALID_ARG, [
																					'$value',
																					'string'
																					]);
		}
		$this->_value = (string)$value;
	}

	/**
	 * Get the length of the current string.
	 *
	 * @return int Length of current string.
	 */
	public function length() {
		return mb_strlen($this->val(), self::DEF_ENCODING);
	}

	/**
	 * Get the number of words in the string.
	 *
	 * @param int $format Specify the return format:
	 * 0 - return number of words
	 * 1 - return an ArrayObject containing all the words found inside the string
	 * 2 - returns an ArrayObject, where the key is the numeric position of the word
	 *                    inside the string and the value is the actual word itself
	 *
	 * @return mixed|ArrayObject An ArrayObject or integer, based on the wanted $format, with the stats about the words in the string.
	 */
	public function wordCount($format = 0) {
		if($format < 1) {
			return str_word_count($this->val(), $format);
		} else {
			return new ArrayObject(str_word_count($this->val(), $format));
		}

	}

	/**
	 * To string implementation.
	 *
	 * @return string Current string.
	 */
	public function __toString() {
		return $this->val();
	}

}