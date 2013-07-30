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
use Webiny\StdLib\StdObject\DateTimeObject\DateTimeObject;
use Webiny\StdLib\StdObjectTrait;

/**
 * Description
 *
 * @package   Webiny\Component\Storage
 */

class File
{
	use StdObjectTrait;

	protected $_key;
	/**
	 * @var Storage
	 */
	protected $_storage;

	protected $_content;
	protected $_contentChanged = false;
	protected $_size;
	protected $_isDirectory;
	protected $_timeModified;

	public function __construct($key, $storage) {
		$this->_storage = $storage;
		$this->_key = $key;
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
			$this->_timeModified = $time = $this->_storage->timeModified($this->_key);
			if($time) {
				$this->_timeModified = $asDateTimeObject ? $this->datetime()->setTimestamp($time) : $time;
			}
		}

		return $this->_timeModified;
	}

	/**
	 * @return mixed
	 */
	public function getSize() {
		if($this->_size == null) {
			$this->_size = $this->_storage->size($this->_key);
		}

		return $this->_size;
	}

	/**
	 * @return mixed
	 */
	public function isDirectory() {
		if($this->_isDirectory == null) {
			$this->_isDirectory = $this->_storage->isDirectory($this->_key);
		}

		return $this->_isDirectory;
	}

	/**
	 * Set file content
	 *
	 * @param mixed $content
	 *
	 * @return $this
	 */
	public function setContent($content) {
		if($this->_content != $content) {
			$this->_contentChanged = true;
		}
		$this->_content = $content;

		return $this;
	}

	/**
	 * Get file content
	 *
	 * @return string|boolean String on success, false if could not read content
	 */
	public function getContent() {
		if($this->_content == null) {
			$this->_content = $this->_storage->read($this->_key);
		}

		return $this->_content;
	}

	public function save() {
		return $this->_storage->write($this->_key, $this->_content);
	}

	public function rename($newKey) {
		if($this->_storage->rename($this->_key, $newKey)) {
			$this->_key = $newKey;

			return true;
		}

		return false;
	}

	public function delete() {
		return $this->_storage->delete($this->_key);
	}
}