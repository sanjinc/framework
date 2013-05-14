<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\StdLib\StdObject\StringObject;

use Webiny\StdLib\StdObject\StdObjectException;

/**
 * StringObject exception class.
 *
 * @package         Webiny\StdLib\StdObject\StringObject
 */
class StringObjectException extends StdObjectException
{

	const MSG_UNABLE_TO_EXPLODE = 101;
	const MSG_INVALID_HASH_ALGO = 102;

	static protected $_messages = [
		101 => 'Unable to explode the string with the given delimiter "%s".',
		102 => 'Invalid hash algorithm provided: "%s". Visit http://www.php.net/manual/en/function.hash-algos.php for more information.'
	];

}