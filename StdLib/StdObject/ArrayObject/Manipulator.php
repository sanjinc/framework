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

/**
 * Manipulator methods for array standard object.
 *
 * @package         WebinyFramework
 * @category		StdLib
 * @subcategory		StdObject
 */
 
trait Manipulator
{
	use \WF\StdLib\StdObject\StdObjectManipulatorTrait;

	abstract function getValue();

	/**
	 * @return ArrayObject
	 */
	abstract function getObject();

	/**
	 * Inserts an element to the end of the array.
	 * If you set both params, that first param is the key, and second is the value, else first param is the value, and the second is ignored.
	 *
	 * @param mixed $k
	 * @param mixed $v
	 */
	public function append($k, $v=null)
	{
		$array = $this->getValue();

		if(!$this->isNull($v))
		{
			$array[$k] = $v;
		}else{
			$array[] = $k;
		}

		$this->updateValue($array);
	}

	/**
	 * Inserts an element at the beginning of the array.
	 * If you set both params, that first param is the key, and second is the value, else first param is the value, and the second is ignored.
	 *
	 * @param mixed $k
	 * @param mixed $v
	 */
	public function prepend($k, $v=null)
	{
		$array = $this->getValue();

		if(!$this->isNull($v))
		{
			$array = [$k, $v]+$array;
		}else{
			$array = $k+$array;
		}

		$this->updateValue($array);
	}
}