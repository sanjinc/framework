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
 * CharType
 * @package Webiny\Component\Entity\Attribute
 */

class CharType extends TypeAbstract{

	protected $_format = '';

	public function format($format){
		$this->_format = $format;
		return $this;
	}
}