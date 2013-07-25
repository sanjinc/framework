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

	public function __construct(DriverInterface $driver){
		$this->_driver = $driver;
	}

	/**
	 * Reads the content of the file
	 *
	 * @param string $key
	 *
	 * @return string|boolean if cannot read content
	 */
	public function read($key){
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
	public function write($key, $content){
		return $this->_driver->write($key, $content);
	}

	/**
	 * Indicates whether the file exists
	 *
	 * @param string $key
	 *
	 * @return boolean
	 */
	public function exists($key){
		return $this->_driver->exists($key);
	}

	/**
	 * Returns an array of all keys (files and directories)
	 *
	 * @return array
	 */
	public function keys(){
		return $this->_driver->keys();
	}

	/**
	 * Returns the last modified time
	 *
	 * @param string $key
	 *
	 * @return integer|boolean An UNIX like timestamp or false
	 */
	public function timeModified($key){
		return $this->_driver->timeModified($key);
	}

	/**
	 * Deletes the file
	 *
	 * @param string $key
	 *
	 * @return boolean
	 */
	public function delete($key){
		return $this->_driver->delete($key);
	}

	/**
	 * Renames a file
	 *
	 * @param string $sourceKey
	 * @param string $targetKey
	 *
	 * @throws \Webiny\Bridge\Storage\StorageException
	 * @return boolean
	 */
	public function rename($sourceKey, $targetKey){
		return $this->_driver->rename($sourceKey, $targetKey);
	}

	/**
	 * Check if key is directory
	 *
	 * @param string $key
	 *
	 * @return boolean
	 */
	public function isDirectory($key){
		return $this->_driver->isDirectory($key);
	}

	/**
	 * Touch a file (change time modified)
	 *
	 * @param $key
	 *
	 * @return mixed
	 */
	public function touch($key){
		return $this->_driver->touch($key);
	}

	/**
	 * Get file size
	 *
	 * @param $key
	 *
	 * @return int|boolean the size of the file in bytes, or false (and generates an error of level E_WARNING) in case of an error.
	 */
	public function size($key){
		return $this->_driver->size($key);
	}
}