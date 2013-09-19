<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Image;

use \Webiny\Component\Storage\File\File;

/**
 * Description
 *
 * @package         Webiny\
 */

interface ImageInterface
{

	/**
	 * Returns the width and height of the image in pixels.
	 *
	 * @return ArrayObject
	 */
	function getSize();

	/**
	 * Get image as a binary string.
	 *
	 * @param array $options List of additional options. Possible values are [quality].
	 *
	 * @return string
	 */
	function getBinary($options = []);

	/**
	 * Get the image mime-type format.
	 * Can be [jpg, jpeg, png, gif].
	 *
	 * @return string
	 */
	function getFormat();

	/**
	 * Sets image mime-type format.
	 *
	 * @param string $format Format name. Supported formats are [jpg, jpeg, png, gif]
	 *
	 * @throws ImageException
	 */
	function setFormat($format);

	/**
	 * Sets the image destination.
	 *
	 * @param File $destination Destination where to store the image.
	 */
	function setDestination(File $destination);

	/**
	 * Crop the image to the given dimensions.
	 *
	 * @param int $width   Width on the new image.
	 * @param int $height  Height of the new image.
	 * @param int $offestX Crop start position on X axis.
	 * @param int $offestY Crop start position on Y axis.
	 *
	 * @return ImageInterface
	 */
	function crop($width, $height, $offestX = 0, $offestY = 0);

	/**
	 * Resize the image to given dimensions.
	 *
	 * @param int  $width                         Width of the new image.
	 * @param int  $height                        Height of the new image.
	 * @param bool $preserveAspectRatio           Do you wish to preserve the aspect ration while resizing. Default is true.
	 *                                            NOTE: If you preserve the aspect ratio, the output image might not match the
	 *                                            defined width and height.
	 *
	 * @return ImageInterface
	 */
	function resize($width, $height, $preserveAspectRatio = true);

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
	 * @return ImageInterface
	 */
	function rotate($angle, $bgColor = null);

	/**
	 * This is a method that combines resize, crop and paste methods in order to generate a thumbnail from the given image.
	 * The benefit of using this function is that the function can automatically combine crop and resize methods together
	 * with the pad feature in order to generate the thumb.
	 *
	 * @param int         $width     Thumb width.
	 * @param int         $height    Thumb height.
	 * @param bool|string $cropOrPad If you set this to 'crop' the method will first resize the image to preserve the
	 *                               aspect ratio and then it will crop the extra pixels to fit the defined width and height.
	 *                               If you set this to 'pad' the method will first do the resize and than
	 *                               it wil create a blank image that has the size of defined width and height and fill it
	 *                               with $padColor, then it will paste the resized image in the center of the new image.
	 * @param null|string $padColor  Parameter that fills the background with the defined color.
	 *                               Following formats are acceptable
	 *                            - "fff"
	 *                            - "ffffff"
	 *                            - array(255,255,255)
	 *
	 * @return $this
	 */
	function thumbnail($width, $height, $cropOrPad = false, $padColor = null);

	/**
	 * Saves the current image to the given location.
	 *
	 * @param \Webiny\Component\Storage\File\File $file
	 * @param array                               $options An array of options. Possible keys are [quality, filters].
	 *
	 * @internal param \Webiny\Component\Storage\File\File $fileName
	 *
	 * @return $this
	 */
	function save(File $file = null, $options = []);

	/**
	 * Output the image into the browser.
	 *
	 * @return string
	 */
	function show();

	/**
	 * Paste another image into this one a the specified dimension.
	 *
	 * @param ImageInterface $image   Image to paste.
	 * @param int            $offsetX Offset on x axis.
	 * @param int            $offsetY Offset on y axis
	 *
	 * @return $this
	 */
	function paste(ImageInterface $image, $offsetX = 0, $offsetY = 0);

	/**
	 * This method returns the instance of the Image object from the bridged library.
	 * The usage of this method is discouraged, but it's necessary for some internal operations.
	 *
	 * @return mixed
	 */
	function getInstance();
}