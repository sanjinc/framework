<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\StdLib\StdObject\DateTimeObject;

use Webiny\StdLib\Exception\ExceptionAbstract;

/**
 * DateTimeObject exception class.
 *
 * @package         Webiny\StdLib\StdObject\DateTimeObject
 */
class DateTimeObjectException extends ExceptionAbstract
{
	const MSG_INVALID_TIMEZONE = 101;
	const MSG_UNABLE_TO_CREATE_FROM_FORMAT = 102;
	const MSG_UNABLE_TO_PARSE = 103;
	const MSG_UNABLE_TO_DIFF = 104;
	const MSG_INVALID_DATE_FORMAT = 105;
	const MSG_DEFAULT_TIMEZONE = 106;
	const MSG_INVALID_FORMAT_FOR_ELEMENT = 107;
	const MSG_INVALID_DATE_INTERVAL = 108;

	static protected $_messages = [
		101 => 'Invalid timezone provided "%s".',
		102 => 'Unable to create date from the given $time and $format',
		103 => 'Unable to parse %s param.',
		104 => 'Unable to diff the two dates.',
		105 => 'Invalid date format "%s".',
		106 => 'Unable to detect the default timezone.',
		107 => 'Invalid format %s for %s',
		108 => 'Invalid datetime interval provided "%s".'
	];
}