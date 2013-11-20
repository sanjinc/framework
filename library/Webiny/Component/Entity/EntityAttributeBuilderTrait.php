<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Entity;


/**
 * EntityAttributeBuilderTrait
 * @package Webiny\Component\Entity
 */

trait EntityAttributeBuilderTrait {

	protected function integer($name = null) {
		$this->_properties[$this->_currentAttribute] = EntityAttributeBuilder::getInstance()->integer($this->_currentAttribute, $name);
		return $this;
	}

	protected function char($name = null) {
		$this->_properties[$this->_currentAttribute] = EntityAttributeBuilder::getInstance()->char($this->_currentAttribute, $name);
		return $this;
	}

	protected function decimal($name = null) {
		$this->_properties[$this->_currentAttribute] = EntityAttributeBuilder::getInstance()->decimal($this->_currentAttribute, $name);
		return $this;
	}

	protected function text($name = null) {
		$this->_properties[$this->_currentAttribute] = EntityAttributeBuilder::getInstance()->decimal($this->_currentAttribute, $name);
		return $this;
	}

	protected function date($name = null) {
		$this->_properties[$this->_currentAttribute] = EntityAttributeBuilder::getInstance()->decimal($this->_currentAttribute, $name);
		return $this;
	}

	protected function select($name = null) {
		$this->_properties[$this->_currentAttribute] = EntityAttributeBuilder::getInstance()->decimal($this->_currentAttribute, $name);
		return $this;
	}

	protected function dynamic($name = null) {
		$this->_properties[$this->_currentAttribute] = EntityAttributeBuilder::getInstance()->decimal($this->_currentAttribute, $name);
		return $this;
	}

	protected function many2one($name = null) {
		$this->_properties[$this->_currentAttribute] = EntityAttributeBuilder::getInstance()->decimal($this->_currentAttribute, $name);
		return $this;
	}

}