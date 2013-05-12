<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\StdLib\StdObject\UrlObject;

use Webiny\StdLib\Exception\ExceptionAbstract;

/**
 * UrlObject exception class.
 *
 * @package         Webiny\StdLib\StdObject\UrlObject
 */
class UrlObjectException extends ExceptionAbstract
{
	const MSG_INVALID_URL = 101;

	static protected $_messages = [
		101 => 'Unable to parse "%s" as a valid url.'
	];
}
