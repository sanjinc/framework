<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Bridge\Image\Imagine;

use Imagine\Image\Box;
use Imagine\Image\Color;
use Webiny\Bridge\Image\ImageLoaderInterface;
use Webiny\Component\Config\ConfigObject;
use Webiny\Component\StdLib\StdLibTrait;
use Webiny\Component\Storage\File\LocalFile;

/**
 * This class is the main bridge to Imagine library.
 *
 * @package         Webiny\
 */

class Imagine implements ImageLoaderInterface
{
	use StdLibTrait;

	/**
	 * @var \Imagine\Gd\Imagine|\Imagine\Gmagick\Imagine|\Imagine\Imagick\Imagine
	 */
	private $_instance;


	/**
	 * Base constructor.
	 *
	 * @param ConfigObject $config
	 */
	function __construct(ConfigObject $config) {
		$library = $this->str($config->get('library', 'gd'))->caseLower()->val();
		$this->_instance = $this->_getLibraryInstance($library);
	}

	/**
	 * Create a library instance based on given library name.
	 *
	 * @param string $library Name of the library. Supported libraries are gd, imagick and gmagick.
	 *
	 * @return \Imagine\Gd\Imagine|\Imagine\Gmagick\Imagine|\Imagine\Imagick\Imagine
	 * @throws ImagineException
	 */
	private function _getLibraryInstance($library) {
		switch ($library) {
			case 'gd':
				return new \Imagine\Gd\Imagine();
				break;

			case 'imagick':
				return new \Imagine\Imagick\Imagine();
				break;

			case 'gmagick':
				return new \Imagine\Gmagick\Imagine();
				break;

			default:
				throw new ImagineException('Unsupported image library "' . $library . '". Cannot create Imagine instance.');
				break;
		}
	}

	/**
	 * Create a blank image with of given dimensions and fill it with $bgColor.
	 *
	 * @param int    $width   Width of the new image.
	 * @param int    $height  Height of the new image.
	 * @param string $bgColor Background color. Following formats are acceptable
	 *                            - "fff"
	 *                            - "ffffff"
	 *                            - array(255,255,255)
	 *
	 * @return \Webiny\Component\Image\ImageInterface
	 */
	function create(\int $width, \int $height, $bgColor = null) {
		$size = new Box($width, $height);
		$color = new Color($bgColor);

		return new Image($this->_instance->create($size, $color));
	}

	/**
	 * Creates a new ImageInterface instance from the given image at the provided path.
	 *
	 * @param LocalFile $image Path to an image on the disk.
	 *
	 * @return \Webiny\Component\Image\ImageInterface
	 */
	function open(LocalFile $image) {
		return new Image($this->_instance->open($image->getAbsolutePath()));
	}

	/**
	 * Create a new ImageInterface instance form the given binary string.
	 *
	 * @param string $string Binary string that holds image information.
	 *
	 * @return \Webiny\Component\Image\ImageInterface
	 */
	function load($string) {
		return new Image($this->_instance->load($string));
	}

	/**
	 * Create a new ImageInterface instance from the given resource.
	 *
	 * @param mixed $resource Resource.
	 *
	 * @return \Webiny\Component\Image\ImageInterface
	 */
	function resource($resource) {
		return new Image($this->_instance->read($resource));
	}
}