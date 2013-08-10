<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\StdLib\StdObject\FileObject;

use Webiny\Component\StdLib\StdObject\ArrayObject\ArrayObject;
use Webiny\Component\StdLib\StdObject\StdObjectValidatorTrait;
use Webiny\Component\StdLib\StdObject\StringObject\StringObject;

/**
 * File object validator trait.
 *
 * @package         Webiny\Component\StdLib\StdObject\FileObject
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
	 * @return bool True if file exists, else false is returned.
	 */
	function exists() {
		return $this->_fileExists;
	}

	/**
	 * Tells if the file is a link.
	 *
	 * @return bool True if the current file path is actually a link to it's real destination. Otherwise, false is returned.
	 */
	function isLink() {
		return $this->_getDriver()->isLink();
	}

	/**
	 * Is file writable.
	 *
	 * @return bool True if file is writable, else false is returned.
	 */
	function isWritable() {
		return $this->_getDriver()->isWritable();
	}

	/**
	 * If file readable.
	 *
	 * @return bool True if file is readable, else false is returned.
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
	 * @throws FileObjectException
	 * @return bool|string False if file is not an image. If file is an image, than we return the image extension.
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
			throw new FileObjectException($e->getMessage());
		}
	}
}