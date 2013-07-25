<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link         http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright    Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license      http://www.webiny.com/framework/license
 * @package      WebinyFramework
 */

namespace Webiny\Bridge\Storage;

use Webiny\StdLib\Exception\ExceptionAbstract;

/**
 * Logger exception class.
 *
 * @package      Webiny\Bridge\Storage
 */

class StorageException extends ExceptionAbstract
{

	const FILE_NOT_FOUND = 101;
	const FAILED_TO_READ = 102;

	static protected $_messages = [
		101 => 'File not found!',
		102 => 'Failed to read file! Make sure the file exists and you have permissions to access it!'
	];
}