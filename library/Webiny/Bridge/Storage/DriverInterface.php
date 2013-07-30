<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Bridge\Storage;

/**
 * DriverInterface
 *
 * @package   Webiny\Bridge\Storage
 */

interface DriverInterface
{
	/**
	 * Reads the content of the file
	 *
	 * @param string $key
	 *
	 * @return string|boolean if cannot read content
	 */
	public function read($key);

	/**
	 * Writes the given File
	 *
	 * @param $key
	 * @param $content
	 *
	 * @return integer|boolean The number of bytes that were written into the file
	 */
	public function write($key, $content);

	/**
	 * Checks whether the file exists
	 *
	 * @param string $key
	 *
	 * @return boolean
	 */
	public function exists($key);

	/**
	 * Returns an array of all keys (files and directories)
	 *
	 * @return array
	 */
	public function keys();

	/**
	 * Returns the last modified time
	 *
	 * @param string $key
	 *
	 * @return integer|boolean A UNIX like timestamp or false
	 */
	public function timeModified($key);

	/**
	 * Deletes the file
	 *
	 * @param string $key
	 *
	 * @return boolean
	 */
	public function delete($key);

	/**
	 * Renames a file
	 *
	 * @param string $sourceKey
	 * @param string $targetKey
	 *
	 * @return boolean
	 */
	public function rename($sourceKey, $targetKey);

	/**
	 * Check if key is directory
	 *
	 * @param string $key
	 *
	 * @return boolean
	 */
	public function isDirectory($key);

	/**
	 * Touch a file (change time modified)
	 *
	 * @param $key
	 *
	 * @return mixed
	 */
	public function touch($key);

	/**
	 * @param $key
	 *
	 * @return int
	 */
	public function size($key);
}