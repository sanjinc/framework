<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */
namespace Webiny\Component\EventManager;

use Webiny\Component\StdLib\Exception\ExceptionAbstract;

/**
 * Exception class for the EventManager component.
 *
 * @package		 Webiny\Component\ServiceManager
 */
class EventManagerException extends ExceptionAbstract
{
	const INVALID_PRIORITY_VALUE = 101;
	const INVALID_EVENT_HANDLER = 102;
	const INVALID_EVENT_NAME = 103;

	static protected $_messages = [
		101 => 'Event listener priority must be greater than 100 and smaller than 1000.',
		102 => 'Event handler must be a valid callable, class name or class instance.',
		103 => 'Event name must be a string at least 1 character long.'
	];

}