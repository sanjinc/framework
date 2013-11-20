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
 * DecimalType
 * @package Webiny\Component\Entity\Attribute
 */

class DecimalType extends TypeAbstract{

	protected $_totalLength = 16;
	protected $_decimalPlaces = 2;

	public function setDigit($totalLength, $decimalPlaces){
		$this->_totalLength = $totalLength;
		$this->_decimalPlaces = $decimalPlaces;
	}
}