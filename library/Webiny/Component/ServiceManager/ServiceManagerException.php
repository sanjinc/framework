<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */
namespace Webiny\Component\ServiceManager;

use Webiny\StdLib\Exception\ExceptionAbstract;

/**
 * Exception class for the ServiceManager component.
 *
 * @package         Webiny\Component\ServiceManager
 */
class ServiceManagerException extends ExceptionAbstract
{

	const SERVICE_DEFINITION_NOT_FOUND = 101;
	const SERVICE_IS_NOT_ABSTRACT = 102;
	const SERVICE_CLASS_KEY_NOT_FOUND = 103;
	const SERVICE_CIRCULAR_REFERENCE = 104;
	const INVALID_SERVICE_ARGUMENTS_TYPE = 105;
	const SERVICE_CLASS_DOES_NOT_EXIST = 106;
	const FACTORY_SERVICE_METHOD_KEY_MISSING = 107;

	static protected $_messages = [
		101 => 'Service "%s" is not defined in services configuration file.',
		102 => 'Service "%s" must containt `abstract` key in order to be available for inheritance.',
		103 => 'Service "%s" must contain `class` or `factory` parameter!',
		104 => 'Service "%s" is creating a circular reference. Check your service definitions and remove circular referencing.',
		105 => 'Service/class "%s" arguments must be in form of an array.',
		106 => 'Service class "%s" does not exist!',
		107 => 'Factory service "%s" `method` key is missing.'
	];

}