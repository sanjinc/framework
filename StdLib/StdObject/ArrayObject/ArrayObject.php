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
 * Array standard object.
 *
 * @package         WebinyFramework
 * @category		StdLib
 * @subcategory		StdObject
 */
 
class ArrayObject implements \WF\StdLib\StdObject\StdObjectInterface
{
	use Manipulator,
		Validator;

	private $_array;

	/**
	 * Constructor.
	 * Set standard object value.
	 *
	 * @param array $value
	 */
	public function __construct(&$value)
	{
		if(!$this->isArray($value))
		{
			if($this->isNull($value))
			{
				$this->_array = array();
			}else{
				$this->exception('Array standard object can only be created from an array.');
			}
		}

		$this->_array = $value;
	}

	/**
	 * Return current standard objects value.
	 *
	 * @return array
	 */
	public function getValue()
	{
		return $this->_array;
	}

	/**
	 * Returns the current standard object instance.
	 *
	 * @return ArrayObject
	 */
	public function getObject()
	{
		return $this;
	}

	/**
	 * To string implementation.
	 *
	 * @return mixed
	 */
	public function __toString()
	{
		return 'Array';
	}

	/**
	 * The update value method is called after each modifier method.
	 * It updates the current value of the standard object.
	 */
	function updateValue(&$value)
	{
		$this->_array = $value;
	}
}