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
use Webiny\StdLib\StdObject\StdObjectException;
use Webiny\StdLib\StdObject\StringObject\ManipulatorTrait;
use Webiny\StdLib\StdObject\StringObject\ValidatorTrait;

/**
 * String standard object.
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
	 * @param string|int $value
	 *
	 * @throws StdObjectException
	 */
	public function __construct($value) {
        if($this->isStringObject($value)){
            return $value;
        }

		if(!$this->isString($value) && !$this->isNumber($value)){
			throw new StdObjectException('StringObject: Unable to create StringObject from the given $value. Only strings and integers are allowed.');
		}
		$this->_value = (string) $value;
	}

	/**
	 * Returns the lenght of the current string.
	 *
	 * @return int
	 */
	public function length() {
		return mb_strlen($this->val(), self::DEF_ENCODING);
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
			return str_word_count($this->val(), $format);
		} else {
			return new ArrayObject(str_word_count($this->val(), $format));
		}

	}

	/**
	 * To string implementation.
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->val();
	}

}