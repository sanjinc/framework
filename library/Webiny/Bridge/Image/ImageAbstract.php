<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Bridge\Image;

use Webiny\Component\StdLib\StdLibTrait;
use Webiny\Component\Storage\File\File;
use Webiny\WebinyTrait;


/**
 * Description
 *
 * @package         Webiny\
 */

abstract class ImageAbstract implements ImageInterface
{
	use StdLibTrait, WebinyTrait;

	/**
	 * @var string File format
	 */
	private $_format = 'png';

	/**
	 * @var File
	 */
	private $_destination;

	/**
	 * @var array
	 */
	static private $_formats = [
		'jpg',
		'jpeg',
		'png',
		'gif'
	];


	/**
	 * Get the image mime-type format.
	 * Can be [jpg, jpeg, png, gif].
	 *
	 * @return string
	 */
	function getFormat() {
		return $this->_format;
	}

	/**
	 * Sets image mime-type format.
	 *
	 * @param string $format Format name. Supported formats are [jpg, jpeg, png, gif]
	 *
	 * @throws ImageException
	 */
	function setFormat($format) {
		if(!in_array($format, self::$_formats)) {
			throw new ImageException('Invalid image format provided. Supported formats are [jpg, jpeg, png, gif].');
		}

		$this->_format = $format;
	}

	/**
	 * Sets the image destination.
	 *
	 * @param File $destination Destination where to store the image.
	 */
	function setDestination(File $destination) {
		$this->_destination = $destination;
	}

	/**
	 * Saves the image in the defined storage.
	 *
	 * @param File  $file    Where to save the image.
	 * @param array $options An array of options. Possible keys are [quality, filters].
	 *
	 * @return bool True if image is save successfully, otherwise false.
	 *
	 * @throws \Exception|ImageException
	 */
	function save(File $file = null, $options = []) {
		if($this->isNull($file)) {
			if($this->isNull($this->_destination)) {
				throw new ImageException('Unable to save the image. Destination storage is not defined.');
			}

			$file = $this->_destination;
		}

		// extract the type
		try {
			$format = $this->str($file->getKey())->explode('.')->last()->caseLower()->val();
			$this->setFormat($format);
		} catch (ImageException $e) {
			throw $e;
		}

		// check quality parameter
		$options['quality'] = !isset($options['quality']) ?
			$this->webiny()->getConfig()->get('components.image.quality', 90) :
			$options['quality'];

		return $file->setContents($this->getBinary($options));
	}
}