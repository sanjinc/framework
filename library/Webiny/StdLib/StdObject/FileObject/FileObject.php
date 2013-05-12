<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\StdLib\StdObject\FileObject;

use SplFileObject;
use Webiny\StdLib\StdObject\StdObjectAbstract;
use Webiny\StdLib\StdObject\StdObjectException;
use Webiny\StdLib\StdObject\StdObjectWrapper;
use Webiny\StdLib\StdObject\StringObject\StringObject;


/**
 * This is the file standard object.
 * Using this standard object you can check if file exits, you can also create and modify files and get file meta data.
 *
 * @package         Webiny\StdLib\StdObject
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
	protected $_value = '';

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
	 * @param string|StringObject $pathToFile Absolute path to the file.
	 *
	 * @throws StdObjectException
	 */
	public function __construct($pathToFile) {
		// assign file path
		$this->_value = StdObjectWrapper::toString($pathToFile);

		// correct directory separator
		$this->_correctDirectorySeparator();

		// load driver and make sure file exists
		$this->_getDriver();

		// check if file already exists
		$realPath = realpath($this->_value);
		if($realPath) {
			$this->_fileExists = true;
			$this->_value = $realPath;
		} else {
			throw new StdObjectException('FileObject: Unable to create, or access, file: ' . $this->_value);
		}
	}

	/**
	 * Returns the file size in bytes.
	 *
	 * @throws StdObjectException
	 * @return int
	 */
	public function getSize() {
		try {
			return $this->_getDriver()->getSize();
		} catch (\Exception $e) {
			throw new StdObjectException('FileObject: Unable to get file size for file: ' . $this->_value . "\n " . $e->getMessage(), 0, $e);
		}
	}

	/**
	 * Returns file name without the directory path.
	 *
	 * @throws StdObjectException
	 * @return mixed|string
	 */
	public function getBasename() {
		try {
			return $this->_getDriver()->getBasename();
		} catch (\Exception $e) {
			throw new StdObjectException('FileObject: Unable to read file basename for file "' . $this->_value . '"', 0, $e);
		}
	}

	/**
	 * Return file extension.
	 *
	 * @throws StdObjectException
	 * @return string
	 */
	public function getExtension() {
		try {
			return $this->_getDriver()->getExtension();
		} catch (\Exception $e) {
			throw new StdObjectException('FileObject: Unable to read file extension "' . $this->_value . '"', 0, $e);
		}
	}

	public function getPath() {
		try {
			return $this->_getDriver()->getPath();
		} catch (\Exception $e) {
			throw new StdObjectException('FileObject: Unable to read file path "' . $this->_value . '"', 0, $e);
		}
	}

	/**
	 * Returns last modified time.
	 *
	 * @return int
	 * @throws StdObjectException
	 */
	public function getMTime() {
		try {
			return $this->_getDriver()->getMTime();
		} catch (\Exception $e) {
			throw new StdObjectException('FileObject: Unable to read file last modified time "' . $this->_value . '"', 0, $e);
		}
	}

	/**
	 * Get current file path, or set a new file path for current file object.
	 *
	 * @param string|null $value
	 *
	 * @return array|\Webiny\StdLib\StdObject\ArrayObject\ArrayObject
	 * @throws \Webiny\StdLib\StdObject\StdObjectException
	 */
	public function val($value = null) {
		if(!$this->isNull($value)) {
			$this->_value = $value;
			$this->_driver = null;
		}

		try {
			return $this->_value;
		} catch (\Exception $e) {
			throw new StdObjectException('FileObject: Unable to get file path "' . $this->_value . '"', 0, $e);
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
	public function getMimeType() {
		try {
			$fi = finfo_open(FILEINFO_MIME_TYPE);
			$info = finfo_file($fi, $this->_value);
			finfo_close($fi);
		} catch (\ErrorException $e) {
			throw new StdObjectException('FileInfo: Unable to read mime type for file: ' . $this->_value);
		}

		return $info;
	}

	/**
	 * Outputs file content to the browser.
	 */
	public function outputFileContent() {
		readfile($this->_value);
	}

	/**
	 * Reads the entire file into memory and returns is as a string.
	 *
	 * @return string
	 */
	public function getFileContent() {
		return file_get_contents($this->_value);
	}

	/**
	 * To string implementation.
	 *
	 * @return mixed
	 */
	public function __toString() {
		return $this->_value;
	}

	/**
	 * Get driver for handling file operations.
	 *
	 * @throws StdObjectException
	 * @return \Webiny\StdLib\StdObject\FileObject\FileObjectDriverInterface
	 */
	protected function _getDriver() {
		if($this->isNull($this->_driver)) {
			try {
				$driver = '\Webiny\StdLib\StdObject\FileObject\Drivers\\' . self::DRIVER;
				$this->_driver = new $driver($this->_value);
				if(!$this->isInstanceOf($this->_driver, 'Webiny\StdLib\StdObject\FileObject\FileObjectDriverInterface')) {
					throw new StdObjectException('FileObject: Driver must implement FileObjectDriverInterface interface');
				}
			} catch (\ErrorException $e) {
				throw new StdObjectException($this->_value . 'FileObject: Unable to create driver instance for driver:
                							' . self::DRIVER);
			}
		}

		return $this->_driver;
	}

	/**
	 * Correct the directory separator and set it to OS default.
	 */
	private function _correctDirectorySeparator() {
		$this->_value = str_replace(array(
										 '/',
										 '\\'
									), DIRECTORY_SEPARATOR, $this->_value);
	}
}