<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Bridge\Storage\Driver;

use Gaufrette\Adapter\Local as GaufretteLocal;
use Webiny\Component\Storage\Driver\DriverInterface;
use Webiny\Component\Storage\Driver\AbsolutePathInterface;
use Webiny\Component\Storage\Driver\DirectoryAwareInterface;
use Webiny\Component\Storage\Driver\LocalHelper;
use Webiny\Component\Storage\Driver\SizeAwareInterface;
use Webiny\Component\Storage\Driver\TouchableInterface;
use Webiny\StdLib\StdObject\StringObject\StringObject;

/**
 * Local storage
 *
 * @package   Webiny\Bridge\Storage\Driver
 */
class Local implements DirectoryAwareInterface, DriverInterface, SizeAwareInterface, AbsolutePathInterface, TouchableInterface
{

	protected $_dateFolderStructure;
	protected $_recentKey = null;
	protected $_directory;
	protected $_create;

	/**
	 * Constructor
	 *
	 * @param string  $directory           Directory of the storage
	 * @param         $publicUrl           Public storage URL
	 * @param bool    $dateFolderStructure If true, will append Y/m/d to the key
	 * @param boolean $create              Whether to create the directory if it does not
	 *                                     exist (default FALSE)
	 *
	 * @throws StorageException
	 */
	public function __construct($directory, $publicUrl, $dateFolderStructure = false, $create = false) {
		$this->_helper = LocalHelper::getInstance();
		$this->_directory = $this->_helper->normalizeDirectoryPath($directory);
		$this->_publicUrl = $publicUrl;
		$this->_dateFolderStructure = $dateFolderStructure;
		$this->_create = $create;

		try {
			$this->_driver = new GaufretteLocal($directory, $create);
		} catch (\RuntimeException $e) {
			throw new StorageException($e->getMessage());
		}
	}


	/**
	 * Returns the last modified time
	 *
	 * @param string $key
	 *
	 * @return integer|boolean An UNIX like timestamp or false
	 */
	public function getTimeModified($key) {
		$this->_recentKey = $key;

		if($this->keyExists($key)) {
			return $this->_driver->mtime($key);
		}

		return false;
	}

	/**
	 * Get file size
	 *
	 * @param $key
	 *
	 * @return int|boolean the size of the file in bytes, or false (and generates an error of level E_WARNING) in case of an error.
	 */
	public function getSize($key) {
		$this->_recentKey = $key;
		if($this->keyExists($key)) {
			return filesize($this->_buildPath($key));
		}

		return false;
	}

	/**
	 * Touch a file (change time modified)
	 *
	 * @param $key
	 *
	 * @return mixed
	 */
	public function touchKey($key) {
		$this->_recentKey = $key;

		return touch($this->_buildPath($key));
	}

	public function renameKey($sourceKey, $targetKey) {
		$this->_recentKey = $sourceKey;
		if($this->keyExists($sourceKey)) {
			return $this->_driver->rename($sourceKey, $targetKey);
		}
		throw new StorageException(StorageException::FILE_NOT_FOUND);
	}

	public function getContent($key) {
		$this->_recentKey = $key;
		$data = $this->_driver->read($key);
		if(!$data) {
			throw new StorageException(StorageException::FAILED_TO_READ);
		}

		return $data;
	}

	/**
	 * Writes the given File
	 *
	 * @param $key
	 * @param $content
	 *
	 * @return integer|boolean The number of bytes that were written into the file
	 */
	public function setContent($key, $content) {
		if($this->_dateFolderStructure) {
			if(!$this->keyExists($key)) {
				$key = new StringObject($key);
				$key = date('Y/m/d/') . $key->trimLeft('/');
			}
		}
		$this->_recentKey = $key;

		return $this->_driver->write($key, $content);
	}

	/**
	 * Checks whether the file exists
	 *
	 * @param string $key
	 *
	 * @return boolean
	 */
	public function keyExists($key) {
		$this->_recentKey = $key;

		return $this->_driver->exists($key);
	}

	/**
	 * Returns an array of all keys (files and directories)
	 *
	 * @return array
	 */
	public function getKeys() {
		return $this->_driver->keys();
	}

	/**
	 * Deletes the file
	 *
	 * @param string $key
	 *
	 * @return boolean
	 */
	public function deleteKey($key) {
		$this->_recentKey = $key;

		return $this->_driver->delete($key);
	}

	/**
	 * Get absolute file path
	 *
	 * @param $key
	 *
	 * @return string Absolute file path
	 */
	public function getAbsolutePath($key) {
		$this->_recentKey = $key;

		return $this->_buildPath($key);
	}

	/**
	 * Returns public file URL
	 *
	 * @param $key
	 *
	 * @return mixed
	 */
	public function getURL($key) {
		return $this->_publicUrl . '/'. ltrim($key, "/");
	}


	/**
	 * Returns most recent file key that was used by a storage
	 *
	 * @return string|null
	 */
	public function getRecentKey() {
		return $this->_recentKey;
	}

	/**
	 * Check if key is directory
	 *
	 * @param string $key
	 *
	 * @return boolean
	 */
	public function isDirectory($key) {
		return $this->_driver->isDirectory($key);
	}

	private function _buildPath($key) {
		return $this->_helper->buildPath($key, $this->_directory, $this->_create);
	}
}