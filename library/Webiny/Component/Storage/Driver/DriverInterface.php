<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Storage\Driver;

/**
 * DriverInterface
 *
 * @package   Webiny\Bridge\Storage
 */

interface DriverInterface
{
	/**
	 * Reads the contents of the file
	 *
	 * @param string $key
	 *
	 * @return string|boolean if cannot read content
	 */
	public function getContents($key);

	/**
	 * Writes the given File
	 *
	 * @param $key
	 * @param $contents
	 *
	 * @return integer|boolean The number of bytes that were written into the file
	 */
	public function setContents($key, $contents);

	/**
	 * Checks whether the file exists
	 *
	 * @param string $key
	 *
	 * @return boolean
	 */
	public function keyExists($key);

	/**
	 * Returns an array of all keys (files and directories)
	 *
	 * For storages that do not support directories, both parameters are irrelevant.
	 *
	 * @param string $key       (Optional) Key of a directory to get keys from. If not set - keys will be read from the storage root.
	 *
	 * @param bool   $recursive (Optional) Read all items recursively
	 *
	 * @return array
	 */
	public function getKeys($key = '', $recursive = false);

	/**
	 * Returns the last modified time
	 *
	 * @param string $key
	 *
	 * @return integer|boolean A UNIX like timestamp or false
	 */
	public function getTimeModified($key);

	/**
	 * Deletes the file
	 *
	 * @param string $key
	 *
	 * @return boolean
	 */
	public function deleteKey($key);

	/**
	 * Renames a file
	 *
	 * @param string $sourceKey
	 * @param string $targetKey
	 *
	 * @return boolean
	 */
	public function renameKey($sourceKey, $targetKey);

	/**
	 * Returns most recent file key that was used by a storage
	 *
	 * @return string|null
	 */
	public function getRecentKey();

	/**
	 * Returns public file URL
	 *
	 * @param $key
	 *
	 * @return mixed
	 */
	public function getURL($key);
}