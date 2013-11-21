<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */
namespace Webiny\Component\Entity;

use Webiny\Component\StdLib\Exception\ExceptionAbstract;

/**
 * Exception class for the ServiceManager component.
 *
 * @package         Webiny\Component\ServiceManager
 */
class EntityException extends ExceptionAbstract
{

	const VALIDATION_FAILED = 101;

	protected $_invalidAttributes = [];

	static protected $_messages = [
		101 => "Entity validation failed with '%s' errors."
	];

	public function setInvalidAttributes($attributes){
		$this->_invalidAttributes = $attributes;
		return $this;
	}

	public function getInvalidAttributes(){
		return $this->_invalidAttributes;
	}

}