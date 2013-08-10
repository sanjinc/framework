<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\StdLib\StdObject\FileObject\Drivers;

use Webiny\Component\StdLib\StdObject\FileObject\FileObjectDriverInterface;
use Webiny\Component\StdLib\StdObject\FileObject\FileObjectException;
use Webiny\Component\StdLib\StdObject\StdObjectException;

/**
 * SplFileObject driver for FileObject Standard Library.
 *
 * @package         Webiny\Component\StdLib\StdObject\FileObject\Drivers
 */
class SplFileObject extends \SplFileObject implements FileObjectDriverInterface
{
	private $_filePath = '';

	/**
	 * @param string $filePath Absolute path to the file.
	 *
	 * @throws SplFileObjectException
	 * @return \Webiny\Component\StdLib\StdObject\FileObject\Drivers\SplFileObject
	 */
	function __construct($filePath) {
		$this->_filePath = $filePath;
		try {
			parent::__construct($filePath, 'c+');
			$this->fseek(0, SEEK_END);
		} catch (\Exception $e) {
			// try to create the file
			try{
				parent::__construct($filePath, 'w+');
			}catch (\Exception $e){
				throw new SplFileObjectException($e->getMessage());
			}
		}

		return $this;
	}

	/**
	 * Delete the current file.
	 *
	 * @return $this
	 * @throws SplFileObjectException
	 */
	function delete() {
		try {
			unlink($this->val());
		} catch (\ErrorException $e) {
			throw new SplFileObjectException(SplFileObjectException::MSG_UNABLE_TO_PERFORM_ACTION, [
																								   'delete',
																								   $this->val()
																								   ]);
		}

		return $this;
	}

	/**
	 * Move the file to given destination.
	 *
	 * @param $destination
	 *
	 * @return $this
	 * @throws SplFileObjectException
	 */
	function move($destination) {
		try {
			$this->copy($destination);
			$this->delete();
		} catch (\ErrorException $e) {
			throw new SplFileObjectException(SplFileObjectException::MSG_UNABLE_TO_PERFORM_ACTION, [
																								   'move',
																								   $this->val()
																								   ]);
		}

		return $this;
	}

	/**
	 * Copy the file to given destination.
	 *
	 * @param $destination
	 *
	 * @return $this
	 * @throws SplFileObjectException
	 */
	function copy($destination) {
		try {
			copy($this->val(), $destination);
		} catch (\ErrorException $e) {
			throw new SplFileObjectException(SplFileObjectException::MSG_UNABLE_TO_PERFORM_ACTION, [
																								   'copy',
																								   $this->val()
																								   ]);
		}

		return $this;
	}

	/**
	 * Rename current file.
	 *
	 * @param $name
	 *
	 * @return $this
	 * @throws SplFileObjectException
	 */
	function rename($name) {
		try {
			rename($this->val(), $name);
		} catch (\ErrorException $e) {
			throw new SplFileObjectException(SplFileObjectException::MSG_UNABLE_TO_PERFORM_ACTION, [
																								   'rename',
																								   $this->val()
																								   ]);
		}

		return $this;
	}

	/**
	 *  Sets access and modification time of file.
	 *
	 * @param null $time
	 *
	 * @return $this
	 * @throws SplFileObjectException
	 */
	function touch($time = null) {
		try {
			if(is_null($time)) {
				touch($this->valid());
			} else {
				touch($this->valid(), $time);
			}
		} catch (\ErrorException $e) {
			throw new SplFileObjectException(SplFileObjectException::MSG_UNABLE_TO_PERFORM_ACTION, [
																								   'touch',
																								   $this->val()
																								   ]);
		}

		return $this;
	}

	function val() {
		return $this->_filePath;
	}
}