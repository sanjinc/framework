<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Entity;

use Webiny\Component\Entity\Attribute\CharType;
use Webiny\Component\Entity\Attribute\DecimalType;
use Webiny\Component\Entity\Attribute\IntegerType;
use Webiny\Component\StdLib\SingletonTrait;


/**
 * EntityBuilder
 * @package Webiny\Component\Entity
 */

class EntityAttributeBuilder
{
	use SingletonTrait;

	private $_properties;
	private $_attribute;

	/**
	 * Set EntityAttributeBuilder context: Entity properties array and current attribute
	 * @param $properties
	 * @param $attribute
	 *
	 * @return $this
	 */
	public function setContext($properties, $attribute){
		$this->_properties = $properties;
		$this->_attribute = $attribute;
		return $this;
	}

	/**
	 * Create a new attribute
	 * @param $attribute
	 *
	 * @return $this
	 */
	public function attr($attribute){
		$this->_attribute = $attribute;
		return $this;
	}

	/**
	 * @param null 		$name
	 *
	 * @return IntegerType
	 */
	public function integer($name = null) {
		return $this->_properties[$this->_attribute] = new IntegerType($this->_attribute, $name);
	}

	/**
	 * @param null 		$name
	 *
	 * @return CharType
	 */
	public function char($name = null) {
		return $this->_properties[$this->_attribute] = new CharType($this->_attribute, $name);
	}

	/**
	 * @param null 		$name
	 *
	 * @return DecimalType
	 */
	public function decimal($name = null) {
		return $this->_properties[$this->_attribute] = new DecimalType($this->_attribute, $name);
	}
}