<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 * @package   WebinyFramework
 */
 namespace WF\StdLib\StdObject;

 /**
  * Standard object validator trait.
  *
  * @package		WebinyFramework
  * @category		StdLib
  * @subcategory	StdObject
  */
 trait StdObjectValidatorTrait
 {
	 use \WF\StdLib\ValidatorTrait;

	 abstract function getValue();
	 abstract function getObject();

	 /**
	  * Checks if base value is empty.
	  * Trim() is always applied to the base value.
	  *
	  * @return bool
	  */
	 public function isEmpty()
	 {
		 if(trim($this->getValue())=="")
		 {
			 return true;
		 }

		 return false;
	 }

	 /**
	  * Checks if two values are the same.
	  * You can also check if the values are of the same variable type.
	  *
	  * @param mixed $compareValue	Value to which the base value will be compared.
	  * @param bool $typeCompare	Do you want to compare the variable type (default: false).
	  * @return bool
	  */
	 public function equals($compareValue, $typeCompare=false)
	 {
		 if($this->getValue()==$compareValue)
		 {
			 return true;
		 }

		 if($typeCompare && $this->getValue()===$compareValue)
		 {
			 return true;
		 }

		 return false;
	 }

	 /**
	  * Check if given value differs from the current standard object.
	  *
	  * @param mixed $compareValue
	  * @param bool $typeCompare
	  * @return bool
	  */
	 public function differs($compareValue, $typeCompare)
	 {
		 return !$this->equals($compareValue, $typeCompare);
	 }
 }