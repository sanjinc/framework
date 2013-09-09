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
use Imagine\Image\Point;
use Webiny\Bridge\Image\ImageAbstract;
use Webiny\Component\Image\ArrayObject;
use Webiny\Component\StdLib\StdLibTrait;

/**
 * This is the image manipulation class for Imagine library.
 *
 * @package         Webiny\Bridge\Image\Imagine
 */

class Image extends ImageAbstract
{
	use StdLibTrait;

	/**
	 * @var \Imagine\Imagick\Image
	 */
	private $_image;


	/**
	 * Base constructor.
	 *
	 * @param \Imagine\Imagick\Image $image
	 */
	function __construct(\Imagine\Imagick\Image $image) {
		$this->_image = $image;
	}

	/**
	 * Get image as a binary string.
	 *
	 * @param array $options An array of options. Possible keys are [quality, filters].
	 *
	 * @return string
	 */
	function getBinary($options = []) {
		return $this->_image->get($this->getFormat(), $options);
	}

	/**
	 * Returns the width and height of the image in pixels.
	 *
	 * @return ArrayObject
	 */
	function getSize() {
		$size = $this->_image->getSize();

		return $this->arr([
						  'width'  => $size->getWidth(),
						  'height' => $size->getHeight()
						  ]);
	}

	/**
	 * Crop the image to the given dimensions.
	 *
	 * @param int $width   Width on the new image.
	 * @param int $height  Height of the new image.
	 * @param int $offestX Crop start position on X axis.
	 * @param int $offestY Crop start position on Y axis.
	 *
	 * @return $this
	 */
	function crop($width, $height, $offestX = 0, $offestY = 0) {
		$pointer = new Point($offestX, $offestY);
		$size = new Box($width, $height);

		$this->_image->crop($pointer, $size);

		return $this;
	}

	/**
	 * Resize the image to given dimensions.
	 *
	 * @param int  $width                         Width of the new image.
	 * @param int  $height                        Height of the new image.
	 * @param bool $preserveAspectRatio           Do you wish to preserve the aspect ration while resizing. Default is true.
	 *                                            NOTE: If you preserve the aspect ratio, the output image might not match the
	 *                                            defined width and height.
	 *
	 * @return $this
	 */
	function resize($width, $height, $preserveAspectRatio = true) {
		if($preserveAspectRatio) {
			$currentSize = $this->getSize();

			$currentAspectRatio = round($currentSize->width / $currentSize->height, 3);
			$aspectRatio = round($width / $height, 3);

			if($currentAspectRatio <> $aspectRatio) {
				if($width < $height) {
					$height = round($width / $currentAspectRatio);
				} else {
					$width = round($height * $currentAspectRatio);
				}
			}
		}

		$size = new Box($width, $height);

		$this->_image->resize($size);

		return $this;
	}

	/**
	 * Rotate the image under the given $angle.
	 *
	 * @param int         $angle   Angle in degrees how much to rotate the image.
	 * @param null|string $bgColor Optional parameter that fills the background with the defined color.
	 *                             Following formats are acceptable
	 *                            - "fff"
	 *                            - "ffffff"
	 *                            - array(255,255,255)
	 *
	 * @return $this
	 */
	function rotate($angle, $bgColor = null) {
		$color = new Color($bgColor);

		$this->_image->rotate($angle, $color);

		return $this;
	}

	/**
	 * Output the image into the browser.
	 *
	 * @return string
	 */
	function show() {
		$this->_image->show($this->getFormat());
	}
}