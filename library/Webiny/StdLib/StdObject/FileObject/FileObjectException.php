<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\StdLib\StdObject\FileObject;

use Webiny\StdLib\StdObject\StdObjectException;

/**
 * FileObject exception class.
 *
 * @package         Webiny\StdLib\StdObject\FileObject
 */
class FileObjectException extends StdObjectException
{
	const MSG_UNABLE_TO_ACCESS = 101;
	const MSG_UNABLE_TO_READ_FILE_PROP = 102;
	const MSG_DRIVER_INTERFACE = 103;
	const MSG_DRIVER_INSTANCE = 104;
	const MSG_UNABLE_TO_PERFORM_ACTION = 105;
	const MSG_FILE_DOESNT_EXIST = 106;

	static protected $_messages = [
		101 => 'Unable to create, or access, the given file "%s".',
		102 => 'Unable to get file %s property for file "%s"',
		103 => 'Driver must implement FileObjectDriverInterface interface.',
		104 => 'Unable to create instance of "%s" driver.',
		105 => 'Unable to perform %s action on file "%s".',
		106 => 'File "%s" does not exist.'
	];
}