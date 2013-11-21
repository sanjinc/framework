<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Entity;

use Webiny\Component\Entity\Attribute\CharAttribute;
use Webiny\Component\Entity\Attribute\DecimalAttribute;
use Webiny\Component\Entity\Attribute\IntegerAttribute;
use Webiny\Component\StdLib\SingletonTrait;


/**
 * EntityBuilder
 * @package Webiny\Component\Entity
 */

class EntityAttributeBuilder
{
	use SingletonTrait;

	private $_attributes;
	private $_attribute;

	/**
	 * Set EntityAttributeBuilder context: Entity attributes array and current attribute
	 * @param $attributes
	 * @param $attribute
	 *
	 * @return $this
	 */
	public function setContext($attributes, $attribute){
		$this->_attributes = $attributes;
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
	 * @param null 		$tableColumn
	 *
	 * @return IntegerAttribute
	 */
	public function integer($tableColumn = null) {
		return $this->_attributes[$this->_attribute] = new IntegerAttribute($this->_attribute, $tableColumn);
	}

	/**
	 * @param null 		$tableColumn
	 *
	 * @return CharAttribute
	 */
	public function char($tableColumn = null) {
		return $this->_attributes[$this->_attribute] = new CharAttribute($this->_attribute, $tableColumn);
	}

	/**
	 * @param null 		$tableColumn
	 *
	 * @return DecimalAttribute
	 */
	public function decimal($tableColumn = null) {
		return $this->_attributes[$this->_attribute] = new DecimalAttribute($this->_attribute, $tableColumn);
	}
}