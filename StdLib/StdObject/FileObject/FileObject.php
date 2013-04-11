<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace WF\StdLib\StdObject\FileObject;

use SplFileObject;
use WF\StdLib\StdObject\StdObjectAbstract;
use WF\StdLib\StdObject\StdObjectException;
use WF\StdLib\StdObject\StringObject\StringObject;


/**
 * This is the file standard object.
 * Using this standard object you can check if file exits, you can also create and modify files and get file meta data.
 *
 * @package         WF\StdLib\StdObject
 */

class FileObject extends StdObjectAbstract
{

	use ValidatorTrait,
		ManipulatorTrait;

	/**
	 * Default driver used for file operations.
	 */
	const DRIVER = 'SplFileObject';

	/**
	 * @var string Absolute path to the file.
	 */
	private $_path = '';

	/**
	 * @var bool Does the file already exist.
	 */
	private $_fileExists = false;

	/**
	 * @var null|mixed|SplFileObject Drive. instance.
	 */
	private $_driver = null;

	/**
	 * Create a new file standard object.
	 * It's not necessary that the file exists.
	 *
	 * @param string $pathToFile Absolute path to the file.
	 *
	 * @throws StdObjectException
	 */
	function __construct($pathToFile) {
		// assign file path
		$this->_path = $pathToFile;

		// correct directory separator
		$this->_correctDirectorySeparator();

		// load driver and make sure file exists
		$this->_getDriver();

		// check if file already exists
		$realPath = realpath($this->_path);
		if($realPath) {
			$this->_fileExists = true;
			$this->_path = $realPath;
		} else {
			throw new StdObjectException('FileObject: Unable to create, or access, file: ' . $this->_path);
		}
	}

	/**
	 * Returns the file size in bytes.
	 *
	 * @throws StdObjectException
	 * @return int
	 */
	function getSize() {
		try {
			return $this->_getDriver()->getSize();
		} catch (\Exception $e) {
			throw new StdObjectException('FileObject: Unable to get file size for file: ' . $this->_path . "\n " . $e->getMessage(), 0, $e);
		}
	}

	/**
	 * Returns file name without the directory path.
	 *
	 * @throws StdObjectException
	 * @return mixed|string
	 */
	function getBasename() {
		try {
			return $this->_getDriver()->getBasename();
		} catch (\Exception $e) {
			throw new StdObjectException('FileObject: Unable to read file basename for file "' . $this->_path . '"', 0, $e);
		}
	}

	/**
	 * Return file extension.
	 *
	 * @throws StdObjectException
	 * @return string
	 */
	function getExtension() {
		try {
			return $this->_getDriver()->getExtension();
		} catch (\Exception $e) {
			throw new StdObjectException('FileObject: Unable to read file extension "' . $this->_path . '"', 0, $e);
		}
	}

	/**
	 * Returns last modified time.
	 *
	 * @return int
	 * @throws StdObjectException
	 */
	function getMTime() {
		try {
			return $this->_getDriver()->getMTime();
		} catch (\Exception $e) {
			throw new StdObjectException('FileObject: Unable to read file last modified time "' . $this->_path . '"', 0, $e);
		}
	}

	/**
	 * Returns absolute path to the file.
	 * The path doesn't contain a trailing slash.
	 *
	 * @return mixed
	 * @throws StdObjectException
	 */
	function getPath() {
		try {
			return $this->_getDriver()->getPath();
		} catch (\Exception $e) {
			throw new StdObjectException('FileObject: Unable to get file path "' . $this->_path . '"', 0, $e);
		}
	}

	/**
	 * Returns file mime type.
	 * This function uses FileInfo extension.
	 * @link http://www.php.net/manual/en/book.fileinfo.php
	 *
	 * @throws StdObjectException
	 * @return string
	 */
	function getMimeType() {
		try {
			$fi = finfo_open(FILEINFO_MIME_TYPE);
			$info = finfo_file($fi, $this->_path);
			finfo_close($fi);
		} catch (\ErrorException $e) {
			throw new StdObjectException('FileInfo: Unable to read mime type for file: ' . $this->_path);
		}

		return $info;
	}

	/**
	 * Return current standard objects value.
	 * Returns the path to the file.
	 *
	 * @return string
	 */
	function getValue() {
		return $this->_path;
	}

	/**
	 * Returns the current standard object instance.
	 *
	 * @return $this
	 */
	function getObject() {
		return $this;
	}

	/**
	 * The update value method is called after each modifier method.
	 * It updates the current value of the standard object.
	 *
	 * @param mixed $value    Passed by reference.
	 */
	function updateValue($value) {
		$this->_path = $value;
		$this->_driver = null;
	}

	/**
	 * Outputs file content to the browser.
	 */
	function outputFileContent() {
		readfile($this->_path);
	}

	/**
	 * Reads the entire file into memory and returns is as a string.
	 *
	 * @return string
	 */
	function getFileContent() {
		return file_get_contents($this->_path);
	}

	/**
	 * To string implementation.
	 *
	 * @return mixed
	 */
	function __toString() {
		return $this->_path;
	}

	/**
	 * Get driver for handling file operations.
	 *
	 * @throws StdObjectException
	 * @return \WF\StdLib\StdObject\FileObject\FileObjectDriverInterface
	 */
	protected function _getDriver() {
		if($this->isNull($this->_driver)) {
			try {
				$driver = '\WF\StdLib\StdObject\FileObject\Drivers\\' . self::DRIVER;
				$this->_driver = new $driver($this->_path);
				if(!$this->isInstanceOf($this->_driver, 'WF\StdLib\StdObject\FileObject\FileObjectDriverInterface')) {
					throw new StdObjectException('FileObject: Driver must implement FileObjectDriverInterface interface');
				}
			} catch (\ErrorException $e) {
				throw new StdObjectException($this->_path . 'FileObject: Unable to create driver instance for driver:
                							' . self::DRIVER);
			}
		}

		return $this->_driver;
	}

	/**
	 * Correct the directory separator and set it to OS default.
	 */
	private function _correctDirectorySeparator() {
		$this->_path = str_replace(array(
										'/',
										'\\'
								   ), DIRECTORY_SEPARATOR, $this->_path);
	}
}