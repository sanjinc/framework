<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace WF\StdLib\StdObject\FileObject;

use WF\StdLib\StdObject\StdObjectException;
use WF\StdLib\StdObject\StdObjectManipulatorTrait;
use WF\StdLib\StdObject\StringObject\StringObject;

/**
 * File object manipulator trait
 *
 * @package         WF\StdLib\StdObject\FileObject
 */
trait ManipulatorTrait
{
	use StdObjectManipulatorTrait;

	/**
	 * @return \WF\StdLib\StdObject\FileObject\FileObjectDriverInterface
	 */
	abstract protected function _getDriver();

	/**
	 * Write the given string into the file.
	 *
	 * @param string $str    String that will be written.
	 * @param null   $length Stop writing after given length.
	 *
	 * @return $this
	 *
	 * @throws \WF\StdLib\StdObject\StdObjectException
	 */
	function write($str, $length = null) {
		try {
			// if file doesn't exist, we must create it manually
			if(!$this->_fileExists) {

				// recursively create folder structure
				if(!file_exists(dirname($this->val()))) {
					mkdir(dirname($this->val()), 0777, true);
				}

				// check the string length
				if(!$this->isNull($length)) {
					$str = new StringObject($str);
					$str->subString(0, $length);
				}

				// create and save the file
				file_put_contents($this->val(), $str);

				// update status
				$this->_fileExists = true;
			} else {
				if($this->isNull($length)) {
					$this->_getDriver()->fwrite($str);
				} else {
					$this->_getDriver()->fwrite($str, $length);
				}

			}
		} catch (\Exception $e) {
			$this->exception('FileObject: Unable to write to file "' . $this->val() . '".' . "\n " . $e->getMessage());
		}

		return $this;
	}

	/**
	 * Truncate the given number of bytes from the file.
	 *
	 * @param null $size
	 *
	 * @throws StdObjectException
	 * @return $this
	 */
	function truncate($size = null) {
		if($this->isNull($size)) {
			$size = $this->getSize();
		}

		try {
			$this->_getDriver()->ftruncate($size);
		} catch (\Exception $e) {
			throw new StdObjectException('FileObject: Unable to truncate the data from the given file: ' . $this->val() .
											 "\n " . $e->getMessage());
		}

		return $this;
	}

	/**
	 * Perform chmod on current file.
	 *
	 * @param int $mode Must be in format xxx. Example 0744
	 *
	 * @return $this
	 * @throws StdObjectException
	 */
	function chmod($mode = 0644) {
		$modeDec = decoct($mode);

		if(!$this->_fileExists) {
			throw new StdObjectException('FileObject: Unable to perform chmod because the file doesn\'t exist: ' . $this->val());
		}

		// few $mode checks
		if(!$this->isInteger($mode)) {
			throw new StdObjectException('FileObject: $mode must be an integer (octal).');
		}

		$modCheck = new StringObject($modeDec);
		$modParts = $modCheck->split();
		if($modParts->count() != 3) { // in octal we lose the zero (0).
			throw new StdObjectException('FileObject: The chmod $mode param must be exactly 4 chars.');
		}
		if($modParts->key(0)->val() > 7 || $modParts->key(1)->val() > 7 || $modParts->key(2)->val() > 7) {
			throw new StdObjectException('FileObject: Invalid chmod $mode value.');
		}

		try {
			chmod($this->val(), $mode);
		} catch (\ErrorException $e) {
			throw new StdObjectException('FileObject: Unable to perform chmod on: ' . $this->val() .
											 " \nPlease check that you have the necessary permissions for this operation.");
		}

		return $this;
	}

	/**
	 * Delete current file.
	 *
	 * @return $this
	 * @throws StdObjectException
	 */
	function delete() {
		try {
			unlink($this->val());
		} catch (\ErrorException $e) {
			throw new StdObjectException('FileObject: Unable to delete the given file: ' . $this->val());
		}

		return $this;
	}

	#########################
	### pointer functions ###
	#########################
	function rewind() {
		// @TODO
	}

	function seek() {
		// @TODO
	}

	function current() {
		// @TODO
	}

	function next() {
		// @TODO
	}

}