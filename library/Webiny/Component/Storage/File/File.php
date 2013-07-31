<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Storage\File;

use Webiny\Bridge\Storage\StorageException;
use Webiny\Component\EventManager\EventManagerTrait;
use Webiny\Component\Storage\Storage;
use Webiny\Component\Storage\StorageEvent;
use Webiny\StdLib\StdObject\DateTimeObject\DateTimeObject;
use Webiny\StdLib\StdObjectTrait;

/**
 * Basic File object that supports all common storage methods
 *
 * @package   Webiny\Component\Storage
 */

class File
{
	use StdObjectTrait, EventManagerTrait;

	/**
	 * @var Storage
	 */
	protected $_storage;
	protected $_key;
	protected $_content;
	protected $_isDirectory;
	protected $_timeModified;
	protected $_url;

	public function __construct($key, Storage $storage) {
		$this->_storage = $storage;
		$this->_key = $key;
	}

	/**
	 * Get file storage
	 *
	 * @return Storage
	 */
	public function getStorage(){
		return $this->_storage;
	}

	/**
	 * Get time modified
	 *
	 * @param bool $asDateTimeObject
	 *
	 * @return int|DateTimeObject UNIX timestamp or DateTimeObject
	 */
	public function getTimeModified($asDateTimeObject = false) {
		if($this->_timeModified == null) {
			$this->_timeModified = $time = $this->_storage->getTimeModified($this->_key);
			if($time) {
				$this->_timeModified = $asDateTimeObject ? $this->datetime()->setTimestamp($time) : $time;
			}
		}

		return $this->_timeModified;
	}

	/**
	 * Is item a directory
	 * @return mixed
	 */
	public function isDirectory() {
		return false;
	}

	/**
	 * Set file content (writes content to storage)<br />
	 *
	 * Fires an event StorageEvent::FILE_SAVED after the file content was written.
	 *
	 * @param mixed $content
	 *
	 * @return $this
	 */
	public function setContent($content) {
		$this->_content = $content;
		if($this->_storage->setContent($this->_key, $this->_content)){
			$this->_key = $this->_storage->getRecentKey();
			$this->eventManager()->fire(StorageEvent::FILE_SAVED,  new StorageEvent($this));
		}
		return false;
	}

	/**
	 * Get file content
	 *
	 * @throws \Webiny\Bridge\Storage\StorageException
	 * @return string|boolean String on success, false if could not read content
	 */
	public function getContent() {
		if($this->_content == null) {
			if($this->_storage->isDirectory($this->_key)){
				throw new StorageException(StorageException::FILE_OBJECT_CAN_NOT_READ_DIRECTORY, [$this->_key]);
			}
			$this->_content = $this->_storage->getContent($this->_key);
		}

		return $this->_content;
	}

	/**
	 * Rename a file<br />
	 *
	 * Fires an event StorageEvent::FILE_RENAMED after the file was renamed.
	 *
	 * @param string $newKey New file name
	 *
	 * @return bool
	 */
	public function rename($newKey) {
		if($this->_storage->renameKey($this->_key, $newKey)) {
			$event = new StorageEvent($this);
			// Set `oldKey` property that will be available only on rename
			$event->oldKey = $this->_key;
			$this->_key = $newKey;
			$this->eventManager()->fire(StorageEvent::FILE_RENAMED, $event);
			return true;
		}
		return false;
	}

	/**
	 * Delete a file<br />
	 *
	 * Fires an event StorageEvent::FILE_DELETED after the file was deleted.
	 *
	 * @return bool
	 */
	public function delete() {
		if($this->_storage->deleteKey($this->_key)) {
			$this->eventManager()->fire(StorageEvent::FILE_DELETED, new StorageEvent($this));

			return true;
		}

		return false;
	}

	/**
	 * Get file key
	 *
	 * @return string
	 */
	public function getKey(){
		return $this->_key;
	}

	/**
	 * Get file public URL
	 * @return string
	 */
	public function getUrl(){
		if($this->_url == null){
			$this->_url = $this->_storage->getURL($this->_key);
		}
		return $this->_url;
	}
}
