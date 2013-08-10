<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Storage;

use Webiny\Component\Storage\Driver\DriverInterface;
use Webiny\Component\Storage\Driver\AbsolutePathInterface;
use Webiny\Component\Storage\Driver\DirectoryAwareInterface;
use Webiny\Component\Storage\Driver\SizeAwareInterface;
use Webiny\Component\Storage\Driver\TouchableInterface;
use Webiny\Component\Storage\StorageException;
use Webiny\Component\StdLib\StdLibTrait;
use Webiny\Component\StdLib\StdObject\DateTimeObject\DateTimeObject;

/**
 * Description
 *
 * @package   Webiny\Component\Storage
 */
class Storage
{
	/**
	 * @var DriverInterface
	 */
	private $_driver = null;

	public function __construct(DriverInterface $driver) {
		$this->_driver = $driver;
	}

	public function getURL($key) {
		if(!$this->isDirectory($key)) {
			return $this->_driver->getURL($key);
		}
	}

	/**
	 * Reads the contents of the file
	 *
	 * @param string key
	 *
	 * @return string|bool if cannot read content
	 */
	public function getContents($key) {
		return $this->_driver->getContents($key);
	}

	/**
	 * Writes the given contents into the file
	 *
	 * @param string $key
	 * @param string $contents
	 *
	 * @return integer|bool The number of bytes that were written into the file
	 */
	public function setContents($key, $contents) {
		return $this->_driver->setContents($key, $contents);
	}

	/**
	 * Indicates whether the key exists
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	public function keyExists($key) {
		return $this->_driver->keyExists($key);
	}

	/**
	 * Returns an array of all keys (files and directories)
	 *
	 * @param string $key Key of a directory to get keys from. If not set - keys will be read from the storage root.
	 *
	 * @param bool   $recursive
	 *
	 * @return array
	 */
	public function getKeys($key = '', $recursive = false) {
		return $this->_driver->getKeys($key, $recursive);
	}

	/**
	 * Returns the last modified time
	 *
	 * @param string $key
	 *
	 * @param bool   $asDateTimeObject (Optional) Return as DateTimeObject if true
	 *
	 * @return UNIX Timestamp or DateTimeObject
	 */
	public function getTimeModified($key, $asDateTimeObject = false) {
		$time = $this->_driver->getTimeModified($key);
		if($asDateTimeObject) {
			$datetime = new DateTimeObject();

			return $datetime->setTimestamp($time);
		}

		return $time;
	}

	/**
	 * Deletes the file
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	public function deleteKey($key) {
		return $this->_driver->deleteKey($key);
	}

	/**
	 * Renames a file
	 *
	 * @param string $sourceKey Old key
	 * @param string $targetKey New key
	 *
	 * @return bool
	 */
	public function renameKey($sourceKey, $targetKey) {
		return $this->_driver->renameKey($sourceKey, $targetKey);
	}

	/**
	 * Check if key is a directory<br />
	 * Requires '\Webiny\Component\Storage\Driver\DirectoryAwareInterface' to be implemented by a Driver class
	 *
	 * @param string $key
	 *
	 * @throws StorageException
	 * @return bool
	 */
	public function isDirectory($key) {
		if($this->supportsDirectories()) {
			return $this->_driver->isDirectory($key);
		}

		return false;
	}

	/**
	 * Touch a file (change time modified)<br />
	 * Requires '\Webiny\Component\Storage\Driver\TouchableInterface' to be implemented by a Driver class
	 *
	 * @param string $key
	 *
	 * @throws StorageException
	 * @return mixed
	 */
	public function touchKey($key) {
		if($this->supportsTouching()) {
			return $this->_driver->touchKey($key);
		}
		throw new StorageException(StorageException::DRIVER_DOES_NOT_SUPPORT_TOUCH, [get_class($this->_driver)]);
	}

	/**
	 * Get file size<br />
	 * Requires '\Webiny\Component\Storage\Driver\SizeAwareInterface' to be implemented by a Driver class
	 *
	 * @param string $key
	 *
	 * @throws StorageException
	 * @return int|bool The size of the file in bytes or false
	 */
	public function getSize($key) {
		if($this->supportsSize()) {
			return $this->_driver->getSize($key);
		}
		throw new StorageException(StorageException::DRIVER_CAN_NOT_ACCESS_SIZE, [get_class($this->_driver)]);
	}

	/**
	 * Get absolute file path<br />
	 * Requires '\Webiny\Component\Storage\Driver\AbsolutePathInterface' to be implemented by a Driver class
	 *
	 * @param $key
	 *
	 * @throws StorageException
	 * @return mixed
	 */
	public function getAbsolutePath($key) {
		if($this->supportsAbsolutePaths()) {
			return $this->_driver->getAbsolutePath($key);
		}
		throw new StorageException(StorageException::DRIVER_DOES_NOT_SUPPORT_ABSOLUTE_PATHS, [get_class($this->_driver)]);
	}

	public function getRecentKey() {
		return $this->_driver->getRecentKey();
	}

	/**
	 * Can this storage handle directories?
	 * @return mixed
	 */
	public function supportsDirectories() {
		return $this->_driver instanceof DirectoryAwareInterface;
	}

	/**
	 * Can this storage touch a file?
	 * @return mixed
	 */
	public function supportsTouching() {
		return $this->_driver instanceof TouchableInterface;
	}

	/**
	 * Can this storage handle absolute paths?
	 * @return mixed
	 */
	public function supportsAbsolutePaths() {
		return $this->_driver instanceof AbsolutePathInterface;
	}

	/**
	 * Can this storage get file size info?
	 * @return mixed
	 */
	public function supportsSize() {
		return $this->_driver instanceof SizeAwareInterface;
	}
}