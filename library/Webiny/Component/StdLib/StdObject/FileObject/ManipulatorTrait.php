<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\StdLib\StdObject\FileObject;

use Webiny\Component\StdLib\StdObject\StdObjectManipulatorTrait;
use Webiny\Component\StdLib\StdObject\StringObject\StringObject;

/**
 * File object manipulator trait
 *
 * @package         Webiny\Component\StdLib\StdObject\FileObject
 */
trait ManipulatorTrait
{
	use StdObjectManipulatorTrait;

	/**
	 * Write the given string into the file.
	 *
	 * @param string $str    String that will be written.
	 * @param null   $length Stop writing after given length.
	 *
	 * @return $this
	 *
	 * @throws FileObjectException
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
			new FileObjectException(FileObjectException::MSG_UNABLE_TO_PERFORM_ACTION, [
																					   'write',
																					   $this->val()
																					   ]);
		}

		return $this;
	}

	/**
	 * Truncate the given number of bytes from the file.
	 *
	 * @param null $size How many bytes to truncate. By default the whole file will be truncated.
	 *
	 * @throws FileObjectException
	 * @return $this
	 */
	function truncate($size = null) {
		if($this->isNull($size)) {
			$size = $this->getSize();
		}

		try {
			$this->_getDriver()->ftruncate($size);
		} catch (\Exception $e) {
			new FileObjectException(FileObjectException::MSG_UNABLE_TO_PERFORM_ACTION, [
																					   'truncate',
																					   $this->val()
																					   ]);
		}

		return $this;
	}

	/**
	 * Perform chmod on current file.
	 *
	 * @param int $mode Must be in format xxx. Example 0744
	 *
	 * @return $this
	 * @throws FileObjectException
	 */
	function chmod($mode = 0644) {
		$modeDec = decoct($mode);

		if(!$this->_fileExists) {
			throw new FileObjectException(FileObjectException::MSG_FILE_DOESNT_EXIST, [$this->val()]);
		}

		// few $mode checks
		if(!$this->isInteger($mode)) {
			throw new FileObjectException(FileObjectException::MSG_INVALID_ARG, [
																				'$mode',
																				'integer'
																				]);
		}

		$modCheck = new StringObject($modeDec);
		$modParts = $modCheck->split();
		if($modParts->count() != 3) { // in octal we lose the zero (0).
			throw new FileObjectException(FileObjectException::MSG_INVALID_ARG_LENGTH, [
																					   '$mode',
																					   'exactly 4 chars'
																					   ]);
		}
		if($modParts->key(0) > 7 || $modParts->key(1) > 7 || $modParts->key(2) > 7) {
			throw new FileObjectException(FileObjectException::MSG_ARG_OUT_OF_RANGE, ['$mode']);
		}

		try {
			chmod($this->val(), $mode);
		} catch (\ErrorException $e) {
			throw new FileObjectException(FileObjectException::MSG_UNABLE_TO_PERFORM_ACTION, [
																							 'chmod',
																							 $this->val()
																							 ]);
		}

		return $this;
	}

	/**
	 * Delete current file.
	 *
	 *
	 * @throws FileObjectException
	 * @return $this
	 */
	function delete() {
		try {
			$this->_getDriver()->delete();
		} catch (\Exception $e) {
			throw new FileObjectException($e->getMessage());
		}

		return $this;
	}

	/**
	 * Move current file to a new destination.
	 *
	 * @param string $destination Path to where you want to place the file.
	 *
	 * @throws FileObjectException
	 * @return $this
	 */
	function move($destination) {
		try {
			$this->_getDriver()->move($destination);
		} catch (\Exception $e) {
			throw new FileObjectException($e->getMessage());
		}

		return $this;
	}

	/**
	 * Copy the current file to a given $destination.
	 *
	 * @param string $destination Path to where to copy the file.
	 *
	 * @throws FileObjectException
	 * @return $this
	 */
	function copy($destination) {
		try {
			$this->_getDriver()->copy($destination);
		} catch (\Exception $e) {
			throw new FileObjectException($e->getMessage());
		}

		return $this;
	}

	/**
	 * Rename the current file.
	 *
	 * @param string $name New file name.
	 *
	 * @throws FileObjectException
	 * @return $this
	 */
	function rename($name) {
		try {
			$this->_getDriver()->rename($name);
		} catch (\Exception $e) {
			throw new FileObjectException($e->getMessage());
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
	 * @throws FileObjectException
	 */
	function touch($time = null) {
		try {
			$this->_getDriver()->touch($time);
		} catch (\Exception $e) {
			throw new FileObjectException($e->getMessage());
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