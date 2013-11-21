<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */
namespace Webiny\Component\Entity\Validation;

use Webiny\Component\StdLib\Exception\ExceptionAbstract;

/**
 * Exception class for the ServiceManager component.
 *
 * @package         Webiny\Component\ServiceManager
 */
class ValidationException extends ExceptionAbstract
{

	const ATTRIBUTE_VALIDATION_FAILED = 101;
	const INVALID_VALIDATOR_TYPE = 102;


	protected $_errorMessages = [];

	static protected $_messages = [
		101 => "Invalid data provided for attribute '%s'.",
		102 => "Invalid validator type: %s"
	];

	public function setErrorMessages($messages){
		$this->_errorMessages = $messages;
		return $this;
	}

	public function getErrorMessages(){
		return $this->_errorMessages;
	}

}