<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\StdLib\StdObject\FileObject;

use SplFileObject;
use Webiny\Component\StdLib\StdObject\StdObjectAbstract;
use Webiny\Component\StdLib\StdObject\StdObjectWrapper;
use Webiny\Component\StdLib\StdObject\StringObject\StringObject;


/**
 * This is the file standard object.
 * Using this standard object you can check if file exits, you can also create and modify files and get file meta data.
 *
 * Example:
 * $fo = new FileObject($pathToFile);
 *
 * @package         Webiny\Component\StdLib\StdObject
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
	 * @throws FileObjectException
	 */
	public function __construct($pathToFile) {
		if(self::isInstanceOf($pathToFile, $this)) {
			$this->_fileExists = true;
			$this->_value = $pathToFile->val();
			$this->_getDriver();

			return;
		}

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
			throw new FileObjectException(FileObjectException::MSG_UNABLE_TO_ACCESS, [$this->_value]);
		}
	}

	/**
	 * Get the file size in bytes.
	 *
	 * @throws FileObjectException
	 * @return int Size of the file in bytes.
	 */
	public function getSize() {
		try {
			return $this->_getDriver()->getSize();
		} catch (\Exception $e) {
			throw new FileObjectException(FileObjectException::MSG_UNABLE_TO_READ_FILE_PROP, [
																							 'size',
																							 $this->_value
																							 ]);
		}
	}

	/**
	 * Get file name without the directory path.
	 *
	 * @throws FileObjectException
	 * @return string The name of the file with its extension and without the directory path.
	 */
	public function getBasename() {
		try {
			return $this->_getDriver()->getBasename();
		} catch (\Exception $e) {
			throw new FileObjectException(FileObjectException::MSG_UNABLE_TO_READ_FILE_PROP, [
																							 'basename',
																							 $this->_value
																							 ]);
		}
	}

	/**
	 * Get file extension.
	 *
	 * @throws FileObjectException
	 * @return string File extension.
	 */
	public function getExtension() {
		try {
			return $this->_getDriver()->getExtension();
		} catch (\Exception $e) {
			throw new FileObjectException(FileObjectException::MSG_UNABLE_TO_READ_FILE_PROP, [
																							 'extension',
																							 $this->_value
																							 ]);
		}
	}

	/**
	 * Get the directory path to the file, without the file name.
	 *
	 * @return string Path to the file, without the file name.
	 * @throws FileObjectException
	 */
	public function getPath() {
		try {
			return $this->_getDriver()->getPath();
		} catch (\Exception $e) {
			throw new FileObjectException(FileObjectException::MSG_UNABLE_TO_READ_FILE_PROP, [
																							 'path',
																							 $this->_value
																							 ]);
		}
	}

	/**
	 * Get last modified time.
	 *
	 * @return int File last modified time as UNIX timestamp.
	 * @throws FileObjectException
	 */
	public function getMTime() {
		try {
			return $this->_getDriver()->getMTime();
		} catch (\Exception $e) {
			throw new FileObjectException(FileObjectException::MSG_UNABLE_TO_READ_FILE_PROP, [
																							 'last modified time',
																							 $this->_value
																							 ]);
		}
	}

	/**
	 * Get current file path, or set a new file path for current file object.
	 *
	 * @param string|null $value If set, current FileObject instance will use that path for file manipulation.
	 *
	 * @return mixed|null|string Either returns the path to the file or current FileObject instance.
	 */
	public function val($value = null) {
		if(!$this->isNull($value)) {
			$this->_value = $value;
			$this->_driver = null; // @TODO: why is this null?
		}

		return $this->_value;
	}

	/**
	 * Get file mime-type.
	 * This function uses FileInfo extension.
	 * @link http://www.php.net/manual/en/book.fileinfo.php
	 *
	 * @throws FileObjectException
	 * @return string The file mime-type.
	 */
	public function getMimeType() {
		try {
			$fi = finfo_open(FILEINFO_MIME_TYPE);
			$info = finfo_file($fi, $this->_value);
			finfo_close($fi);
		} catch (\ErrorException $e) {
			throw new FileObjectException(FileObjectException::MSG_UNABLE_TO_READ_FILE_PROP, [
																							 'mime-type',
																							 $this->_value
																							 ]);
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
	 * @return string File content.
	 */
	public function getFileContent() {
		return file_get_contents($this->_value);
	}

	/**
	 * To string implementation.
	 *
	 * @return mixed Full path to the file.
	 */
	public function __toString() {
		return $this->_value;
	}

	/**
	 * Get driver for handling file operations.
	 *
	 * @throws FileObjectException
	 * @return FileObjectDriverInterface
	 */
	protected function _getDriver() {
		if($this->isNull($this->_driver)) {
			try {
				$driver = '\Webiny\Component\StdLib\StdObject\FileObject\Drivers\\' . self::DRIVER;
				$this->_driver = new $driver($this->_value);
				if(!$this->isInstanceOf($this->_driver, 'Webiny\Component\StdLib\StdObject\FileObject\FileObjectDriverInterface')
				) {
					throw new FileObjectException(FileObjectException::MSG_DRIVER_INTERFACE);
				}
			} catch (\ErrorException $e) {
				throw new FileObjectException(FileObjectException::MSG_DRIVER_INSTANCE, [$driver]);
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