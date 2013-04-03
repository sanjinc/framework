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

use WF\StdLib\StdObject\StdObjectException;
use WF\StdLib\StdObject\StdObjectManipulatorTrait;
use WF\StdLib\StdObject\StringObject\StringObject;

/**
 * Manipulator methods for array standard object.
 *
 * @package         WF\StdLib\StdObject\ArrayObject
 */
trait ManipulatorTrait
{
	use StdObjectManipulatorTrait;

	abstract function getValue();

	/**
	 * @return ArrayObject
	 */
	abstract function getObject();

	/**
	 * Inserts an element to the end of the array.
	 * If you set both params, that first param is the key, and second is the value,
	 * else first param is the value, and the second is ignored.
	 *
	 * @param mixed $k
	 * @param mixed $v
	 *
	 * @return $this
	 */
	public function append($k, $v = null) {
		$array = $this->getValue();

		if(!$this->isNull($v)) {
			$array[$k] = $v;
		} else {
			$array[] = $k;
		}

		$this->updateValue($array);

		return $this;
	}

	/**
	 * Inserts an element at the beginning of the array.
	 * If you set both params, that first param is the key, and second is the value,
	 * else first param is the value, and the second is ignored.
	 *
	 * @param mixed $k
	 * @param mixed $v
	 *
	 * @return $this
	 */
	public function prepend($k, $v = null) {
		$array = $this->getValue();

		if(!$this->isNull($v)) {
			$array = array_reverse($array, true);
			$array[$k] = $v;
			$array = array_reverse($array, true);
		} else {
			array_unshift($array, $k);
		}

		$this->updateValue($array);

		return $this;
	}

	/**
	 * Removes the first element from the array.
	 *
	 * @return $this
	 */
	public function removeFirst() {
		$array = $this->getValue();
		array_shift($array);

		$this->updateValue($array);

		return $this;
	}

	/**
	 * Removes the last element from the array.
	 *
	 * @return $this
	 */
	public function removeLast() {
		$array = $this->getValue();
		array_pop($array);

		$this->updateValue($array);

		return $this;
	}

	/**
	 * Remove the element from the array under the given $key.
	 *
	 * @param string $key Key that will be removed from the array.
	 *
	 * @return $this
	 */
	public function removeKey($key) {
		if($this->key($key)) {
			$array = $this->getValue();
			unset($array[$key]);

			$this->updateValue($array);
		}

		return $this;
	}

	/**
	 * Implode the array with the given $glue.
	 *
	 * @param string $glue String that will be used to put elements together.
	 *
	 * @return StringObject
	 */
	public function implode($glue) {
		$array = $this->getValue();
		$string = implode($glue, $array);

		return new StringObject($string);
	}

	/**
	 * Split an array into chunks.
	 *
	 * @param int  $size          Chunk size.
	 * @param bool $preserve_keys Do you want ot preserve keys.
	 *
	 * @return ArrayObject
	 * @throws StdObjectException
	 */
	public function chunk($size, $preserve_keys = false) {
		try {
			$chunk = array_chunk($this->getValue(), $size, $preserve_keys);
		} catch (\ErrorException $e) {
			throw new StdObjectException('ArrayObject: ' . $e->getMessage());
		}

		return new ArrayObject($chunk);
	}

	/**
	 * Change the case of all keys in current ArrayObject.
	 *
	 * @param string $case Case to which you want to covert array keys. Can be 'lower' or 'upper'.
	 *
	 * @return $this
	 * @throws StdObjectException
	 */
	public function changeKeyCase($case) {
		// validate case
		$case = new StringObject($case);
		$case->caseLower();
		$realCase = '';
		if($case->equals('lower')) {
			$realCase = CASE_LOWER;
		} else {
			if($case->equals('upper')) {
				$realCase = CASE_UPPER;
			} else {
				throw new StdObjectException('ArrayObject: $case must be either "lower" or "upper".');
			}
		}

		$this->updateValue(array_change_key_case($this->getValue(), $realCase));

		return $this;
	}

	/**
	 * Use current array as keys and will them with $value.
	 * @link http://php.net/manual/en/function.array-fill-keys.php
	 *
	 * @param mixed $value Value to use for filling.
	 *
	 * @throws StdObjectException
	 * @return $this
	 */
	public function fillKeys($value) {
		try {
			$arr = array_fill_keys($this->getValue(), $value);
		} catch (\ErrorException $e) {
			throw new StdObjectException('ArrayObject: ' . $e->getMessage());
		}


		$this->updateValue($arr);

		return $this;
	}

	/**
	 * Fill array with values.
	 * Number of items is defined by $num param, and start index is defined by $start param.
	 * If you have items in your current array, they will be removed.
	 *
	 * @param int   $start The first index of the returned array.
	 * @param int   $num   Number of elements to insert. Must be greater than zero.
	 * @param mixed $value Value to use for filling.
	 *
	 * @return $this
	 * @throws StdObjectException
	 */
	public function fill($start, $num, $value) {
		if(!$this->isNumber($start) || !$this->isNumber($num)) {
			throw new StdObjectException('ArrayObject: $start and $num must be integers.');
		}

		if($num <= 0) {
			throw new StdObjectException('ArrayObject: $num must be greate than zero.');
		}

		$this->updateValue(array_fill($start, $num, $value));

		return $this;
	}

	/**
	 * Filter array values by using a callback function.
	 *
	 * @param string $callback
	 *
	 * @return $this
	 * @throws StdObjectException
	 */
	public function filter($callback = '') {
		if($callback != '' && !$this->isCallable($callback)) {
			throw new StdObjectException('ArrayObject: $callback must be a callable function or a method.');
		}

		$this->updateValue(array_filter($this->getValue(), $callback));

		return $this;
	}

	/**
	 * Exchanges all keys with their associated values in the array.
	 * Note: This function can only flip STRING and INTEGER values. Other types will throw a E_WARNING
	 * (no exceptions, sorry :( )
	 *
	 * @throws StdObjectException
	 * @return $this
	 */
	public function flip() {
		try {
			$this->updateValue(array_flip($this->getValue()));
		} catch (\ErrorException $e) {
			throw new StdObjectException('ArrayObject: ' . $e->getMessage());
		}


		return $this;
	}

	/**
	 * Remove all elements from the current array that are not present in the given $array.
	 * Keys ARE NOT used in the comparison.
	 *
	 * @param $array Array for comparison.
	 *
	 * @return $this
	 */
	public function intersect($array) {
		$this->updateValue(array_intersect($this->getValue(), $array));

		return $this;
	}

	/**
	 * Remove all elements from the current array that are not present in the given $array.
	 * This function uses array keys for comparison unlike 'intersect' method.
	 *
	 * @param array  $array    Array for comparison
	 * @param string $callback Optional callback function that can be uses for comparison.
	 *
	 * @return $this
	 * @throws StdObjectException
	 */
	public function intersectAssoc($array, $callback = '') {
		if($callback != '') {
			if(!$this->isCallable($callback)) {
				throw new StdObjectException('ArrayObject: $callback must be a callable function or a method.');
			}

			$this->updateValue(array_intersect_uassoc($this->getValue(), $array, $callback));
		} else {
			$this->updateValue(array_intersect_assoc($this->getValue(), $array));
		}

		return $this;
	}

	/**
	 * Remove all elements from the current array that are not present in the given $array.
	 * This function compares ONLY array keys.
	 *
	 * @param array  $array    Array for comparison
	 * @param string $callback Optional callback function that can be uses for comparison.
	 *
	 * @return $this
	 * @throws StdObjectException
	 */
	public function intersectKey($array, $callback = '') {
		if($callback != '') {
			if(!$this->isCallable($callback)) {
				throw new StdObjectException('ArrayObject: $callback must be a callable function or a method.');
			}

			$this->updateValue(array_intersect_ukey($this->getValue(), $array, $callback));
		} else {
			$this->updateValue(array_intersect_key($this->getValue(), $array));
		}

		return $this;
	}

	/**
	 * Apply the $callback function to all elements of current array.
	 *
	 * @param string $callback A callable function.
	 *
	 * @return $this
	 * @throws StdObjectException
	 */
	public function map($callback) {
		if(!$this->isCallable($callback)) {
			throw new StdObjectException('ArrayObject: $callback must be a callable function or a method.');
		}

		$this->updateValue(array_map($callback, $this->getValue()));

		return $this;
	}

	/**
	 * Merge given $array with current array.
	 *
	 * @param array|ArrayObject $array
	 *
	 * @return $this
	 */
	public function merge($array) {
		if($this->isInstanceOf($array, $this)) {
			$array = $array->getValue();
		}

		$this->updateValue(array_merge($this->getValue(), $array));

		return $this;
	}

	/**
	 * Sort the array by its values.
	 * This sort function take two flags. One defines the sort algorithm, and the other othe, the sort direction.
	 * Default behavior equals to the standard asort function.
	 *
	 * @param int $direction In which direction you want to sort. You can use SORT_ASC or SORT_DESC.
	 * @param int $sortFlag  Which sort algorithm. SORT_REGULAR | SORT_NUMERIC | SORT_STRING | SORT_LOCALE_STRING | SORT_NATURAL
	 *
	 * @return $this
	 * @throws StdObjectException
	 */
	public function sortAssoc($direction = SORT_ASC, $sortFlag = SORT_REGULAR) {

		try {
			$arr = $this->getValue();
			if($direction == SORT_ASC) {
				asort($arr, $sortFlag);
			} else {
				arsort($arr, $sortFlag);
			}
		} catch (\ErrorException $e) {
			throw new StdObjectException('ArrayObject: ' . $e->getMessage());
		}

		$this->updateValue($arr);

		return $this;
	}

	/**
	 * Sort the array by its values.
	 * This sort function take two flags. One defines the sort algorithm, and the other othe, the sort direction.
	 * Default behavior equals to the standard asort function.
	 *
	 * @param int $direction In which direction you want to sort. You can use SORT_ASC or SORT_DESC.
	 * @param int $sortFlag  Which sort algorithm. SORT_REGULAR | SORT_NUMERIC | SORT_STRING | SORT_LOCALE_STRING | SORT_NATURAL
	 *
	 * @return $this
	 * @throws StdObjectException
	 */
	public function sortKey($direction = SORT_ASC, $sortFlag = SORT_REGULAR) {
		try {
			$arr = $this->getValue();
			if($direction == SORT_DESC) {
				krsort($arr, $sortFlag);
			} else {
				ksort($arr, $sortFlag);
			}
		} catch (\ErrorException $e) {
			throw new StdObjectException('ArrayObject: ' . $e->getMessage());
		}

		$this->updateValue($arr);

		return $this;
	}

	/**
	 * Sort an assoc array by a value inside the defined $field.
	 *
	 * @param string $field
	 * @param int    $direction
	 * @param int    $sortFlag
	 *
	 * @throws StdObjectException
	 * @return $this
	 */
	public function sortField($field, $direction = SORT_ASC, $sortFlag = SORT_NUMERIC) {
		// check array
		if(!$this->isArray($this->first()->getValue())) {
			throw new StdObjectException('ArrayObject: You can only sort a multi-dimensional array.');
		}

		// check if key is present in the array
		if(!$this->first()->key($field)) {
			throw new StdObjectException('ArrayObject: $field is not present in the current array.');
		}

		// do the sorting
		$tempArray = array();
		$thisArray = $this->getValue();

		foreach ($thisArray as $mk => $m) {
			$tempArray[$m[$field]][] = $mk;
		}

		if($direction == SORT_DESC) {
			krsort($tempArray, $sortFlag);
		} else {
			ksort($tempArray, $sortFlag);
		}

		$newArray = array();
		foreach ($tempArray as $tk => $tv) {
			foreach ($tv as $ttk => $ttv) {
				$newArray[$ttv] = $thisArray[$ttv];
			}
		}

		$this->updateValue($newArray);
		unset($tempArray);
		unset($thisArray);

		return $this;
	}

	/**
	 * Pad array to the specified length with a value.
	 *
	 * @param int   $size  New size of the array
	 * @param mixed $value Value to pad if array is smaller than pad_size.
	 *
	 * @return $this
	 * @throws StdObjectException
	 */
	public function pad($size, $value) {
		try {
			$arr = array_pad($this->getValue(), $size, $value);
		} catch (\ErrorException $e) {
			throw new StdObjectException('ArrayObject: ' . $e->getMessage());
		}

		$this->updateValue($arr);

		return $this;
	}

	/**
	 * Returns random elements from current array.
	 *
	 * @param int $num How many object you want to return.
	 *
	 * @return ArrayObject
	 * @throws StdObjectException
	 */
	public function rand($num = 1) {
		try {
			$arr = array_rand($this->getValue(), $num);
		} catch (\ErrorException $e) {
			throw new StdObjectException('ArrayObject: ' . $e->getMessage());
		}

		if(!$this->isArray($arr)) {
			$arr = [$arr];
		}

		return new ArrayObject($arr);
	}

	/**
	 * This method replaces the values of current array with the values from $array for the matching keys.
	 * If a key from the current array exists in the $replacements array, its value will be replaced by the value
	 * from $replacements.
	 * If the key exists in $replacements, and not in the current array, it will be created in the current array.
	 * If a key only exists in the current array, it will be left as is.
	 *
	 * If $recursive is TRUE it will recurse into arrays and apply the same process to the inner value.
	 *
	 * @param array|ArrayObject $replacements
	 * @param bool              $recursive
	 *
	 * @throws StdObjectException
	 *
	 * @return $this
	 */
	public function replace($replacements, $recursive = false) {
		if($this->isInstanceOf($replacements, $this)) {
			$replacements = $replacements->getValue();
		} else {
			if(!$this->isArray($replacements)) {
				throw new StdObjectException('ArrayObject: $array must be the of array or ArrayObject.');
			}
		}

		try {
			if($recursive) {
				$arr = array_replace_recursive($this->getValue(), $replacements);
			} else {
				$arr = array_replace($this->getValue(), $replacements);
			}

		} catch (\ErrorException $e) {
			throw new StdObjectException('ArrayObject: ' . $e->getMessage());
		}

		$this->updateValue($arr);

		return $this;
	}

	/**
	 * Reverse the order of elements in the current array.
	 *
	 * @return $this
	 */
	public function reverse() {
		$this->updateValue(array_reverse($this->getValue()));

		return $this;
	}

	/**
	 * Slice a portion of current array and discard the remains.
	 *
	 * @param int  $offset       From where to start slicing.
	 * @param int  $length       How many elements to take.
	 * @param bool $preserveKeys Do you want to preserve the keys from the current array. Default is true.
	 *
	 * @return $this
	 * @throws StdObjectException
	 */
	public function slice($offset, $length, $preserveKeys = true) {
		if(!$this->isNumber($offset) || !$this->isNumber($length)) {
			throw new StdObjectException('ArrayObject: Both $offset and $length must be integers.');
		}

		try {
			$arr = array_slice($this->getValue(), $offset, $length, $preserveKeys);
		} catch (\ErrorException $e) {
			throw new StdObjectException('ArrayObject: ' . $e->getMessage());
		}

		$this->updateValue($arr);

		return $this;
	}

	/**
	 * Remove a portion of the array and replace it with something else.
	 *
	 * @param int  $offset       From where to start slicing.
	 * @param int  $length       How many elements to take.
	 * @param bool $replacement  With what to replace the selected portion.
	 *
	 * @return $this
	 * @throws StdObjectException
	 */
	public function splice($offset, $length, $replacement) {
		if(!$this->isNumber($offset) || !$this->isNumber($length)) {
			throw new StdObjectException('ArrayObject: Both $offset and $length must be integers.');
		}

		try {
			$arr = array_splice($this->getValue(), $offset, $length, $replacement);
		} catch (\ErrorException $e) {
			throw new StdObjectException('ArrayObject: ' . $e->getMessage());
		}

		$this->updateValue($arr);

		return $this;
	}

	/**
	 * Remove duplicates from the array.
	 *
	 * @param int $sortFlag   The optional parameter that may be used to modify the sorting behavior.
	 *                        Possible values are: SORT_STRING, SORT_NUMERIC, SORT_REGULAR and SORT_LOCALE_STRING
	 *
	 * @return $this
	 */
	public function unique($sortFlag = SORT_STRING) {
		$this->updateValue(array_unique($this->getValue(), $sortFlag));

		return $this;
	}

	/**
	 * Applies the user-defined function to each element of the input array.
	 * If $recursive the function will recurse into deeper arrays.
	 *
	 * @param mixed $function  Which function will be called for each array value.
	 * @param bool  $recursive Go into deeper levels of the array. Default: false.
	 * @param null  $userData  Optional data that can be passed along to the user callable $function.
	 *
	 * @return $this
	 * @throws StdObjectException
	 */
	public function walk($function, $recursive = false, $userData = null) {
		if(!$this->isCallable($function)) {
			throw new StdObjectException('ArrayObject: $function must be a callable function or method.');
		}

		try {
			$arr = $this->getValue();
			if($recursive) {
				array_walk_recursive($arr, $function, $userData);
			} else {
				array_walk($arr, $function, $userData);
			}
		} catch (\ErrorException $e) {
			throw new StdObjectException('ArrayObject: ' . $e->getMessage());
		}

		$this->updateValue($arr);

		return $this;
	}

	/**
	 * Shuffle elements in the array.
	 *
	 * @return $this
	 */
	public function shuffle() {
		$this->updateValue(shuffle($this->getValue()));

		return $this;
	}

	/**
	 * Compare two arrays or ArrayObjects and returns an ArrayObject containing all the values
	 * from current ArrayObject that are not present in any of the other array.
	 *
	 * @param      $array       Array to which to compare
	 * @param bool $compareKeys Do you want to compare array keys also. Default is false.
	 *
	 * @return ArrayObject
	 * @throws StdObjectException
	 */
	public function diff($array, $compareKeys = false) {
		if($this->isInstanceOf($array, $this)) {
			$array = $array->getValue();
		} else {
			if(!$this->isArray($array)) {
				throw new StdObjectException('ArrayObject: You can only compare one ArrayObject to another ArrayObject or array.');
			}
		}

		if($compareKeys) {
			$this->updateValue(array_diff_assoc($this->getValue(), $array));

			return $this;
		} else {
			$this->updateValue(array_diff($this->getValue(), $array));

			return $this;
		}
	}

	/**
	 * Compare the keys from two arrays or ArrayObjects and returns an ArrayObject containing all the values
	 * from current ArrayObject whose keys are not present in any of the other arrays.
	 *
	 * @param      $array       Array to which to compare
	 *
	 * @return ArrayObject
	 * @throws StdObjectException
	 */
	public function diffKeys($array) {
		if($this->isInstanceOf($array, $this)) {
			$array = $array->getValue();
		} else {
			if(!$this->isArray($array)) {
				throw new StdObjectException('ArrayObject: You can only compare one ArrayObject to another ArrayObject or array.');
			}
		}

		$this->updateValue(array_diff_key($this->getValue(), $array));

		return $this;
	}
}