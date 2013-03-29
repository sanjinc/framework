<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 * @package   WebinyFramework
 */

namespace WF\StdLib\StdObject\ArrayObject;

use Traversable;
use WF\StdLib\StdObject\StdObjectAbstract;
use WF\StdLib\StdObject\ArrayObject\ManipulatorTrait;
use WF\StdLib\StdObject\ArrayObject\ValidatorTrait;
use WF\StdLib\StdObject\StdObjectException;
use WF\StdLib\StdObject\StringObject\StringObject;

/**
 * Array standard object.
 *
 * @package         WF\StdLib\StdObject\ArrayObject
 */
class ArrayObject extends StdObjectAbstract implements \IteratorAggregate
{
	use ManipulatorTrait,
		ValidatorTrait;

	private $_array;

	/**
	 * Constructor.
	 * Set standard object value.
	 *
	 * @param null|array $array
	 * @param null|array $values      - Array of values that will be combined with $array.
	 *                                See http://www.php.net/manual/en/function.array-combine.php for more info.
	 * 								  $array param is used as key array.
	 *
	 * @throws StdObjectException
	 */
	public function __construct($array = null, $values = null) {
		if(!$this->isArray($array)) {
			if($this->isNull($array)) {
				$this->_array = array();
			} else {
				throw new StdObjectException('ArrrayObject: Array standard object can only be created from an array.');
			}
		} else {
			if($this->isArray($values)) {
				// check if both arrays have the same number of values
				if(count($array) != count($values)){
					throw new StdObjectException('ArrayObject: Both arrays must have equal number of items');
				}
				$this->_array = array_combine($array, $values);
			} else {
				$this->_array = $array;
			}
		}
	}

	/**
	 * Return the sum of all elements inside the array.
	 *
	 * @return number
	 */
	public function sum() {
		return array_sum($this->getValue());
	}

	/**
	 * Return an ArrayObject containing only the keys of current array.
	 *
	 * @return ArrayObject
	 */
	public function keys() {
		return new ArrayObject(array_keys($this->getValue()));
	}

	/**
	 * Return an ArrayObject containing only the values of current array.
	 *
	 * @return ArrayObject
	 */
	public function values() {
		return new ArrayObject(array_values($this->getValue()));
	}

	/**
	 * Return the last element in the array.
	 * If the element is array, ArrayObject is returned, else StringObject is returned.
	 *
	 * @return StringObject|ArrayObject
	 */
	public function last() {
		$arr = $this->getValue();
		$last = end($arr);

		if($this->isArray($last)){
			return new ArrayObject($last);
		}else{
			return new StringObject($last);
		}
	}

	/**
	 * Returns the first element in the array.
	 * If the element is array, ArrayObject is returned, else StringObject is returned.
	 *
	 * @return StringObject|ArrayObject
	 */
	public function first() {
		$arr = $this->getValue();
		$first = reset($arr);

		if($this->isArray($first)){
			return new ArrayObject($first);
		}else{
			return new StringObject($first);
		}
	}

	/**
	 * Returns the number of elements inside the array.
	 *
	 * @return int
	 */
	public function count() {
		return count($this->getValue());
	}

	/**
	 * Counts the occurrences of the same array values and groups them into an associate array.
	 * NOTE: This function can only count array values that are type of STRING of INTEGER.
	 *
	 * @throws StdObjectException
	 * @return ArrayObject
	 */
	public function countValues() {
		try{
			/**
			 * We must mute errors in this function because it throws a E_WARNING message if array contains something
			 * else than STRING or INTEGER.
			 */
			@$result = array_count_values($this->getValue());
			return new ArrayObject($result);
		}catch (\ErrorException $e){
			throw new StdObjectException('ArrayObject: countValues() can only count STRING and INTEGER values');
		}

	}

	/**
	 * Return current standard objects value.
	 *
	 * @return array
	 */
	public function getValue() {
		return $this->_array;
	}

	/**
	 * Returns the current standard object instance.
	 *
	 * @return ArrayObject
	 */
	public function getObject() {
		return $this;
	}

	/**
	 * To string implementation.
	 *
	 * @return mixed
	 */
	public function __toString() {
		return 'Array';
	}

	/**
	 * The update value method is called after each modifier method.
	 * It updates the current value of the standard object.
	 */
	function updateValue($value) {
		$this->_array = $value;
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Retrieve an external iterator
	 * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
	 * @return Traversable An instance of an object implementing <b>Iterator</b> or
	 * <b>Traversable</b>
	 */
	public function getIterator() {
		return new \ArrayIterator($this->_array);
	}
}