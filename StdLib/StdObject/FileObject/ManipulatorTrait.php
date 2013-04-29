<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace WF\StdLib\StdObject\FileObject;

use Exception;
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

	static public function fileExists($path){
		return file_exists($path);
	}

	static public function dirExists($path){
		return is_dir($path);
	}

	/**
	 * Write the given string into the file.
	 *
	 * @param string $str    String that will be written.
	 * @param null   $length Stop writing after given length.
	 *
	 * @return $this
	 *
	 * @throws StdObjectException
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
		} catch (Exception $e) {
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
		} catch (Exception $e) {
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
		if($modParts->key(0) > 7 || $modParts->key(1) > 7 || $modParts->key(2) > 7) {
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
	 *
	 * @throws \Exception|StdObjectException
	 * @return $this
	 */
	function delete() {
		try{
			$this->_getDriver()->delete();
		}catch (StdObjectException $e){
			throw $e;
		}

		return $this;
	}

	/**
	 * Move current file to a new destination.
	 *
	 * @param string $destination Path to where you want to place the file.
	 *
	 * @throws \Exception|StdObjectException
	 * @return $this
	 */
	function move($destination) {
		try{
			$this->_getDriver()->move($destination);
		}catch (StdObjectException $e){
			throw $e;
		}
		return $this;
	}

	/**
	 * Copy the current file to a given $destination.
	 *
	 * @param string $destination Path to where to copy the file.
	 *
	 * @throws \Exception|StdObjectException
	 * @return $this
	 */
	function copy($destination) {
		try{
			$this->_getDriver()->copy($destination);
		}catch (StdObjectException $e){
			throw $e;
		}

		return $this;
	}

	/**
	 * Rename the current file.
	 *
	 * @param string $name New file name.
	 *
	 * @throws \Exception|StdObjectException
	 * @return $this
	 */
	function rename($name) {
		try{
			$this->_getDriver()->rename($name);
		}catch (StdObjectException $e){
			throw $e;
		}

		return $this;
	}

	/**
	 * Attempts to set the access and modification times of the file named in the filename parameter to the value given in time.
	 * Note that the access time is always modified, regardless of the number of parameters.
	 *
	 * @param null $time
	 *
	 * @return $this
	 * @throws \Exception|\WF\StdLib\StdObject\StdObjectException
	 */
	function touch($time = null){
		try{
			$this->_getDriver()->touch($time);
		}catch (StdObjectException $e){
			throw $e;
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