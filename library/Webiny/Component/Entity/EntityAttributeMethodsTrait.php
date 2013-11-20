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

trait EntityAttributeMethodsTrait {

	protected function label($label = null) {
		$this->_properties[$this->_currentAttribute]->setLabel($label);
		return $this;
	}

	protected function defaultValue($value = null) {
		$this->_properties[$this->_currentAttribute]->setDefaultValue($value);
		return $this;
	}

	protected function format($format){
		$this->_properties[$this->_currentAttribute]->setFormat($format);
		return $this;
	}

	protected function digit($totalLength, $decimalPlaces){
		$this->_properties[$this->_currentAttribute]->setDigit($totalLength, $decimalPlaces);
		return $this;
	}
}