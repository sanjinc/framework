<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Entity\Attribute;

use Webiny\Component\Entity\Validation\Validator;
use Webiny\Component\Entity\EntityAttributeBuilder;
use Webiny\Component\StdLib\StdLibTrait;


/**
 * AttributeAbstract
 * @package Webiny\Component\Entity\Attribute
 */

abstract class AttributeAbstract
{
	use StdLibTrait;

	protected $_attribute = '';
	protected $_tableColumn = '';
	protected $_label = '';
	protected $_message = '';
	protected $_help = '';
	protected $_tooltip = '';
	protected $_validation = false;
	protected $_defaultValue = null;
	protected $_value = null;
	protected $_dirty = false;

	/**
	 * @param string $attribute
	 * @param string $tableColumn
	 *
	 * @return AttributeAbstract
	 */
	function __construct($attribute, $tableColumn) {
		$this->_attribute = $attribute;
		if($this->isNull($tableColumn)) {
			$tableColumn = $attribute;
		}
		$this->_tableColumn = $tableColumn;
	}

	function __toString() {
		if($this->isNull($this->_value)) {
			return '';
		}

		return $this->_value;
	}

	public function isDirty(){
		return  $this->_dirty;
	}

	/**
	 * Create new attribute or get name of current attribute
	 *
	 * @param null|string $attribute
	 *
	 * @return Builder
	 */
	public function attr($attribute = null) {
		if($this->isNull($attribute)){
			return $this->_attribute;
		}
		return EntityAttributeBuilder::getInstance()->attr($attribute);
	}


	/**
	 * @param null $defaultValue
	 *
	 * @return $this
	 */
	public function defaultValue($defaultValue = null) {
		if($this->isNull($defaultValue)) {
			return $this->_defaultValue;
		}
		$this->_defaultValue = $defaultValue;

		return $this;
	}

	/**
	 * @param string $help
	 *
	 * @return $this
	 */
	public function help($help = null) {
		if($this->isNull($help)) {
			return $this->_help;
		}
		$this->_help = $help;

		return $this;
	}

	/**
	 * @param string $label
	 *
	 * @return $this
	 */
	public function label($label = null) {
		if($this->isNull($label)) {
			return $this->_label;
		}

		$this->_label = $label;

		return $this;
	}

	/**
	 * @param string $message
	 *
	 * @return $this
	 */
	public function message($message = null) {
		if($this->isNull($message)) {
			return $this->_message;
		}
		$this->_message = $message;

		return $this;
	}

	/**
	 * Get attribute table column (used for query building)
	 *
	 * @return string
	 */
	public function tableColumn() {
		return $this->_tableColumn;
	}

	/**
	 * @param string $tooltip
	 *
	 * @return $this
	 */
	public function tooltip($tooltip = null) {
		if($this->isNull($tooltip)) {
			return $this->_tooltip;
		}
		$this->_tooltip = $tooltip;

		return $this;
	}

	/**
	 * Get or set validation rules
	 * @throws \Exception
	 * @return $this
	 */
	public function validation() {
		$args = $this->arr(func_get_args());

		if($args->count() == 0) {
			return $this->_validation;
		}

		if($this->isArray($args[0])) {
			$args = $args[0];
		}

		$this->_validation = $args;

		return $this;
	}

	/**
	 * @param null $value
	 *
	 * @return $this
	 */
	public function value($value = null) {
		if($this->isNull($value)) {
			return $this->_value;
		}

		$this->validate($value);
		$this->_value = $value;
		return $this;
	}

	/**
	 * Perform validation against given value
	 *
	 * @param $value
	 *
	 * @throws ValidationException
	 * @return $this
	 */
	public function validate($value) {
		// Perform value validation
		Validator::getInstance()->validate($this, $value);
		return $this;
	}
}