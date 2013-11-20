<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Entity\Attribute;

use Webiny\Component\Entity\EntityAttributeBuilder;
use Webiny\Component\StdLib\StdLibTrait;
use Webiny\Component\StdLib\ValidatorTrait;


/**
 * TypeAbstract
 * @package Webiny\Component\Entity\Attribute
 */

class TypeAbstract
{
	use ValidatorTrait;

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
		if($this->isNull($name)){
			$name = $attribute;
		}
		$this->_name = $name;
	}

	function __toString(){
		if($this->isNull($this->_value)){
			return '';
		}
		return $this->_value;
	}

	/**
	 * @param $attribute
	 *
	 * @return EntityAttributeBuilder
	 */
	public function attr($attribute){
		return EntityAttributeBuilder::getInstance()->attr($attribute);
	}


	/**
	 * @param null $defaultValue
	 *
	 * @return $this
	 */
	public function defaultValue($defaultValue = null) {
		if($this->isNull($defaultValue)){
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
		if($this->isNull($help)){
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
		if($this->isNull($label)){
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
		if($this->isNull($message)){
			return $this->_message;
		}
		$this->_message = $message;

		return $this;
	}

	/**
	 * @param string $name
	 *
	 * @return $this
	 */
	public function name($name = null) {
		if($this->isNull($name)){
			return $this->_name;
		}
		$this->_name = $name;

		return $this;
	}

	/**
	 * @param string $tooltip
	 *
	 * @return $this
	 */
	public function tooltip($tooltip = null) {
		if($this->isNull($tooltip)){
			return $this->_tooltip;
		}
		$this->_tooltip = $tooltip;

		return $this;
	}

	/**
	 * @param boolean $validation
	 *
	 * @return $this
	 */
	public function validation($validation = null) {
		if($this->isNull($validation)){
			return $this->_validation;
		}
		$this->_validation = $validation;

		return $this;
	}

	/**
	 * @param null $value
	 *
	 * @return $this
	 */
	public function value($value = null) {
		if($this->isNull($value)){
			return $this->_value;
		}
		$this->_value = $value;

		return $this;
	}

	public function validate($value){
		// Perform value validation
		return $this;
	}
}