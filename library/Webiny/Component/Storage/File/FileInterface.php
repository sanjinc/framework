<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Storage\File;

use Webiny\Component\Storage\Storage;

/**
 * Basic File interface
 *
 * @package   Webiny\Component\Storage\File
 */

interface FileInterface
{

	/**
	 * Constructor
	 *
	 * @param string  $key     File key
	 * @param Storage $storage Storage to use
	 */
	public function __construct($key, Storage $storage);

	/**
	 * Get file storage
	 *
	 * @return Storage
	 */
	public function getStorage();

	/**
	 * Get file key
	 *
	 * @return string
	 */
	public function getKey();

	/**
	 * Get file public URL
	 *
	 * @return string
	 */
	public function getUrl();

	/**
	 * Get file contents
	 *
	 * @throws StorageException
	 *
	 * @return string|boolean String on success, false if could not read content
	 */
	public function getContents();

	/**
	 * Get time modified
	 *
	 * @param bool $asDateTimeObject
	 *
	 * @return int|DateTimeObject UNIX timestamp or DateTimeObject
	 */
	public function getTimeModified($asDateTimeObject = false);

	/**
	 * Set file content (writes content to storage)<br />
	 *
	 * Fires an event StorageEvent::FILE_SAVED after the file content was written.
	 *
	 * @param mixed $content
	 *
	 * @return $this
	 */
	public function setContent($content);

	/**
	 * Rename a file<br />
	 *
	 * Fires an event StorageEvent::FILE_RENAMED after the file was renamed.
	 *
	 * @param string $newKey New file name
	 *
	 * @return bool
	 */
	public function rename($newKey);

	/**
	 * Delete a file<br />
	 *
	 * Fires an event StorageEvent::FILE_DELETED after the file was deleted.
	 *
	 * @return bool
	 */
	public function delete();
}
