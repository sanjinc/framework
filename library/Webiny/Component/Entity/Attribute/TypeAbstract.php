<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Entity\Attribute;


/**
 * TypeAbstract
 * @package Webiny\Component\Entity\Attribute
 */

class TypeAbstract
{

	/**
	 * @var string
	 */
	protected $_attribute = '';
	protected $_name = '';
	protected $_label = '';
	protected $_message = '';
	protected $_help = '';
	protected $_tooltip = '';
	protected $_validation = false;
	protected $_defaultValue = null;
	protected $_value = null;

	/**
	 * @param string $attribute
	 * @param string $name
	 */
	function __construct($attribute, $name) {
		$this->_attribute = $attribute;
		if($name == null){
			$name = $attribute;
		}
		$this->_name = $name;
	}

	function __toString(){
		if($this->_value == null){
			return '';
		}
		return $this->_value;
	}


	/**
	 * @param null $defaultValue
	 *
	 * @return $this
	 */
	public function setDefaultValue($defaultValue) {
		$this->_defaultValue = $defaultValue;

		return $this;
	}

	/**
	 * @return null
	 */
	public function getDefaultValue() {
		return $this->_defaultValue;
	}

	/**
	 * @param string $help
	 *
	 * @return $this
	 */
	public function setHelp($help) {
		$this->_help = $help;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getHelp() {
		return $this->_help;
	}

	/**
	 * @param string $label
	 *
	 * @return $this
	 */
	public function setLabel($label) {
		$this->_label = $label;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getLabel() {
		return $this->_label;
	}

	/**
	 * @param string $message
	 *
	 * @return $this
	 */
	public function setMessage($message) {
		$this->_message = $message;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getMessage() {
		return $this->_message;
	}

	/**
	 * @param string $name
	 *
	 * @return $this
	 */
	public function setName($name) {
		$this->_name = $name;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->_name;
	}

	/**
	 * @param string $tooltip
	 *
	 * @return $this
	 */
	public function setTooltip($tooltip) {
		$this->_tooltip = $tooltip;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getTooltip() {
		return $this->_tooltip;
	}

	/**
	 * @param boolean $validation
	 *
	 * @return $this
	 */
	public function setValidate($validation) {
		$this->_validation = $validation;

		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getValidate() {
		return $this->_validation;
	}

	/**
	 * @param null $value
	 *
	 * @return $this
	 */
	public function setValue($value) {
		$this->_value = $value;

		return $this;
	}

	/**
	 * @return null
	 */
	public function getValue() {
		return $this->_value;
	}

	public function validate($value){
		// Perform value validation
		return $this;
	}
}