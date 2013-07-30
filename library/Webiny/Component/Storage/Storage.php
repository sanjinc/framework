<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Storage;

use Webiny\Bridge\Storage\DriverInterface;
use Webiny\Bridge\Storage\StorageException;
use Webiny\StdLib\StdLibTrait;
use Webiny\StdLib\StdObject\DateTimeObject\DateTimeObject;

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
		return $this->_driver->getURL($key);
	}

	/**
	 * Reads the content of the file
	 *
	 * @param string key
	 *
	 * @return string|boolean if cannot read content
	 */
	public function read($key) {
		return $this->_driver->read($key);
	}

	/**
	 * Writes the given content into the file
	 *
	 * @param string $key
	 * @param string $content
	 *
	 * @return integer|boolean The number of bytes that were written into the file
	 */
	public function write($key, $content) {
		return $this->_driver->write($key, $content);
	}

	/**
	 * Indicates whether the file exists
	 *
	 * @param File $file
	 *
	 * @return boolean
	 */
	public function exists(File $file) {
		return $this->_driver->exists($file);
	}

	/**
	 * Returns an array of all keys (files and directories)
	 *
	 * @return array
	 */
	public function keys() {
		return $this->_driver->keys();
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
	public function timeModified($key, $asDateTimeObject = false) {
		$time = $this->_driver->timeModified($key);
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
	 * @return boolean
	 */
	public function delete($key) {
		return $this->_driver->delete($key);
	}

	/**
	 * Renames a file
	 *
	 * @param string $sourceKey Old key
	 * @param string $targetKey New key
	 *
	 * @return boolean
	 */
	public function rename($sourceKey, $targetKey) {
		return $this->_driver->rename($sourceKey, $targetKey);
	}

	/**
	 * Check if key is a directory<br />
	 * Requires '\Webiny\Component\Storage\Driver\DirectoryAwareInterface' to be implemented by a Driver class
	 *
	 * @param string $key
	 *
	 * @throws \Webiny\Bridge\Storage\StorageException
	 * @return boolean
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
	 * @throws \Webiny\Bridge\Storage\StorageException
	 * @return mixed
	 */
	public function touch($key) {
		if($this->supportsTouching()) {
			return $this->_driver->touch($key);
		}
		throw new StorageException(StorageException::DRIVER_DOES_NOT_SUPPORT_TOUCH, [get_class($this->_driver)]);
	}

	/**
	 * Get file size<br />
	 * Requires '\Webiny\Component\Storage\Driver\SizeAwareInterface' to be implemented by a Driver class
	 *
	 * @param string $key
	 *
	 * @throws \Webiny\Bridge\Storage\StorageException
	 * @return int|boolean The size of the file in bytes or false
	 */
	public function size($key) {
		if($this->supportsSize()) {
			return $this->_driver->size($key);
		}
		throw new StorageException(StorageException::DRIVER_CAN_NOT_ACCESS_SIZE, [get_class($this->_driver)]);
	}

	/**
	 * Get absolute file path<br />
	 * Requires '\Webiny\Component\Storage\Driver\AbsolutePathInterface' to be implemented by a Driver class
	 *
	 * @param $key
	 *
	 * @return mixed
	 * @throws \Webiny\Bridge\Storage\StorageException
	 */
	public function getAbsolutePath($key) {
		if($this->supportsAbsolutePaths()){
			return $this->_driver->getAbsolutePath($key);
		}
		throw new StorageException(StorageException::DRIVER_DOES_NOT_SUPPORT_ABSOLUTE_PATHS, [get_class($this->_driver)]);
	}

	/**
	 * Can this storage handle directories?
	 * @return mixed
	 */
	public function supportsDirectories() {
		return $this->isInstanceOf($this->_driver, '\Webiny\Component\Storage\Driver\DirectoryAwareInterface');
	}

	/**
	 * Can this storage touch a file?
	 * @return mixed
	 */
	public function supportsTouching() {
		return $this->isInstanceOf($this->_driver, '\Webiny\Component\Storage\Driver\TouchableInterface');
	}

	/**
	 * Can this storage handle absolute paths?
	 * @return mixed
	 */
	public function supportsAbsolutePaths() {
		return $this->isInstanceOf($this->_driver, '\Webiny\Component\Storage\Driver\AbsolutePathInterface');
	}

	/**
	 * Can this storage get file size info?
	 * @return mixed
	 */
	public function supportsSize() {
		return $this->isInstanceOf($this->_driver, '\Webiny\Component\Storage\Driver\SizeAwareInterface');
	}
}