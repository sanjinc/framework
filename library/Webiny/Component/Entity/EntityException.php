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

	static protected $_messages = [
		101 => 'Failed to validate entity data.'
	];

}