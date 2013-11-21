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
 * DecimalAttribute
 * @package Webiny\Component\Entity\Attribute
 */

class DecimalAttribute extends AttributeAbstract
{

	protected $_totalLength = 16;
	protected $_decimalPlaces = 2;

	/**
	 * Get or set length and decimal places
	 *
	 * When getting values, an array wil be returned: [$totalLength, $decimalPlaces]
	 *
	 * @param $totalLength
	 * @param $decimalPlaces
	 *
	 * @return $this|array
	 */
	public function digit($totalLength = null, $decimalPlaces = null) {
		if($this->isNull($totalLength) || $this->isNull($decimalPlaces)) {
			return [
				$this->_totalLength,
				$this->_decimalPlaces
			];
		}

		$this->_totalLength = $totalLength;
		$this->_decimalPlaces = $decimalPlaces;

		return $this;
	}
}