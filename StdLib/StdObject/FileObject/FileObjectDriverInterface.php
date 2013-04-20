<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace WF\StdLib\StdObject\FileObject;

/**
 * Interface for FileObject drivers.
 * Drivers are classes used for file operations.
 *
 * @package		 WF\StdLib\StdObject\FileObject
 */
 
interface FileObjectDriverInterface
{
	/**
	 * @param string $filePath Absolute path to the file.
	 *
	 * @return mixed Driver instance.
	 */
	function __construct($filePath);

	function getSize();
	function getBasename();
	function getExtension();
	function getPath();
	function getMTime();
	function isFile();
	function isLink();
	function isWritable();
	function isReadable();
	function fwrite($str, $lenght = null);
	function ftruncate($site);
	function delete();
	function move($destination);
	function copy($destination);
	function rename($name);
	function touch($time = null);
}