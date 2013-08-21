<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Http\Request;

use Webiny\Component\Http\Request\Files\File;
use Webiny\Component\Http\Request\Files\FilesException;
use Webiny\Component\StdLib\StdLibTrait;

/**
 * File Http component.
 *
 * @package         Webiny\Component\Http
 */

class Files
{
	use StdLibTrait;

	private $_fileBag;
	private $_fileObject;

	/**
	 * Constructor.
	 */
	function __construct() {
		$this->_fileBag = $this->arr($_FILES);
	}

	/**
	 * Get the File object for the given $name.
	 * If you have a multi-dimensional upload field name, than you should pass the optional $arrayOffset param to get the
	 * right File object.
	 *
	 * @param string   $name        Name of the upload field.
	 * @param null|int $arrayOffset Optional array offset for multi-dimensional upload fields.
	 *
	 * @throws Files\FilesException
	 * @return File
	 */
	function get($name, $arrayOffset = null) {
		// first some validations
		if(!$this->_fileBag->keyExists($name)) {
			throw new FilesException('Upload field with name "' . $name . '" was not found in the $_FILES array.');
		}

		// check to see if we have already created the file object
		if(isset($this->_fileObject[$name])) {
			$fileObject = $this->_getFileObject($name, $arrayOffset);
			if($fileObject) {
				return $fileObject;
			}
		}

		// create and return File object
		$file = $this->_fileBag->key($name);
		if(is_null($arrayOffset)) {
			$fileObject = $this->_createFileObject($file, $arrayOffset);

			return $fileObject;
		} else {
			if(!isset($file['name'][$arrayOffset])) {
				throw new FilesException('Uploaded file with name "' . $name . '" and
											offset "' . $arrayOffset . '" was not found in the $_FILES array.');
			}

			$fileObject = $this->_createFileObject($file, $arrayOffset);

			return $fileObject;
		}
	}

	/**
	 * Create the File object.
	 *
	 * @param array $file
	 * @param null  $arrayOffset
	 *
	 * @return File
	 */
	private function _createFileObject($file, $arrayOffset = null) {
		if(!is_null($arrayOffset)) {
			$fileObject = new File($file['name'][$arrayOffset],
								   $file['tmp_name'][$arrayOffset],
								   $file['type'][$arrayOffset],
								   $file['error'][$arrayOffset],
								   $file['size'][$arrayOffset]);
			$this->_fileObject[$file['name']][$arrayOffset] = $fileObject;
		} else {
			$fileObject = new File($file['name'],
								   $file['tmp_name'],
								   $file['type'],
								   $file['error'],
								   $file['size']);
			$this->_fileObject[$file['name']] = $fileObject;
		}

		return $fileObject;
	}

	/**
	 * Check if we have already create a File object of the given $name.
	 *
	 * @param  string $name
	 * @param null    $arrayOffset
	 *
	 * @return bool|File False if the object is not created, otherwise File is returned.
	 */
	private function _getFileObject($name, $arrayOffset = null) {
		if(!is_null($arrayOffset)) {
			if(isset($this->_fileObject[$name]) && $this->isArray($this->_fileObject[$name]) &&
				isset($this->_fileObject[$name][$arrayOffset]) &&
				$this->isInstanceOf($this->_fileObject[$name][$arrayOffset], 'Webiny\Component\Http\Request\Files\File')
			) {
				return $this->_fileObject[$name][$arrayOffset];
			}
		} else {
			if(isset($this->_fileObject[$name]) &&
				$this->isInstanceOf($this->_fileObject[$name], 'Webiny\Component\Http\Request\Files\File')
			) {
				return $this->_fileObject[$name];
			}
		}

		return false;
	}
}