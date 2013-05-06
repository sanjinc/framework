<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\StdLib\StdObject\FileObject;

use Webiny\StdLib\StdObject\ArrayObject\ArrayObject;
use Webiny\StdLib\StdObject\StdObjectException;
use Webiny\StdLib\StdObject\StdObjectValidatorTrait;
use Webiny\StdLib\StdObject\StringObject\StringObject;

/**
 * File object validator trait.
 *
 * @package         Webiny\StdLib\StdObject\FileObject
 */
trait ValidatorTrait
{
	use StdObjectValidatorTrait;

	private $_imageMimeTypes = [
		'image/bmp'           => 'bmp',
		'image/x-windows-bmp' => 'bmp',
		'image/gif'           => 'gif',
		'image/x-icon'        => 'ico',
		'image/jpeg'          => 'jpeg',
		'image/png'           => 'png',
		'image/tiff'          => 'tiff',
		'image/x-tiff'        => 'tiff'
	];

	/**
	 * @return \SplFileObject
	 */
	abstract protected function _getDriver();

	/**
	 * Does the file exist on the disk.
	 *
	 * @return bool
	 */
	function exists() {
		return $this->_fileExists;
	}

	/**
	 * Tells if the object references a regular file.
	 *
	 * @return bool
	 */
	function isFile() {
		return $this->_getDriver()->isFile();
	}

	/**
	 * Tells if the file is a link.
	 *
	 * @return bool
	 */
	function isLink() {
		return $this->_getDriver()->isLink();
	}

	/**
	 * Is file writable.
	 *
	 * @return bool
	 */
	function isWritable() {
		return $this->_getDriver()->isWritable();
	}

	/**
	 * If file readable.
	 *
	 * @return bool
	 */
	function isReadable() {
		return $this->_getDriver()->isReadable();
	}

	/**
	 * Checks if file is an image.
	 * The check if based on file mime-type, NOT file extension.
	 * You can optionally set a list o accepted image types to which the mime type will be matched.
	 *
	 * @param null|array|ArrayObject $types   The image must be of this type. Available types are: gif, jpeg, png, tiff,
	 *                                        ico, bmp.
	 *
	 * @throws StdObjectException
	 * @return bool|StringObject
	 */
	function isImage($types = null) {
		try {
			$mimeType = $this->getMimeType();
			$arr = new ArrayObject($this->_imageMimeTypes);
			if(($value = $arr->keyExists($mimeType)) !== false) {
				if($this->isNull($types)) {
					return $value;
				} else {
					$arr = new ArrayObject($types);
					if($arr->inArray($value)) {
						return $value;
					}
				}
			}

			return false;
		} catch (\Exception $e) {
			throw new StdObjectException('FileInfo: Unable to perform a check if file is an image', 0, $e);
		}
	}
}