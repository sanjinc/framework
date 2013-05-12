<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\StdLib\StdObject\FileObject\Drivers;

use Webiny\StdLib\Exception\ExceptionAbstract;

/**
 * Exception class for SplFileObject driver.
 *
 * @package         Webiny\StdLib\StdObject\FileObject\Drivers
 */
class SplFileObjectException extends ExceptionAbstract
{
	const MSG_UNABLE_TO_PERFORM_ACTION = 101;

	static protected $_messages = [
		101 => 'Unable to perform %s action on file "%s".',
	];
}