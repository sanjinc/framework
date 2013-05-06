<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\StdLib\StdObject\FileObject\Drivers;

use Webiny\StdLib\StdObject\FileObject\FileObjectDriverInterface;
use Webiny\StdLib\StdObject\StdObjectException;

/**
 * SplFileObject driver for FileObject Standard Library.
 *
 * @package         Webiny\StdLib\StdObject\FileObject\Drivers
 */
class SplFileObject extends \SplFileObject implements FileObjectDriverInterface
{
	private $_filePath = '';

	/**
	 * @param string $filePath Absolute path to the file.
	 *
	 * @throws \Webiny\StdLib\StdObject\StdObjectException
	 * @return \Webiny\StdLib\StdObject\FileObject\Drivers\SplFileObject
	 */
	function __construct($filePath) {
		$this->_filePath = $filePath;
		try {
			parent::__construct($filePath, 'w');
		} catch (\Exception $e) {
			throw new StdObjectException('FileObject: Unable to construct driver: SplFileObject. ' . $e->getMessage());
		}

		return $this;
	}

	/**
	 * Delete the current file.
	 *
	 * @return $this
	 * @throws \Webiny\StdLib\StdObject\StdObjectException
	 */
	function delete() {
		try {
			unlink($this->val());
		} catch (\ErrorException $e) {
			throw new StdObjectException('FileObject: Unable to delete the given file: ' . $this->val());
		}

		return $this;
	}

	/**
	 * Move the file to given destination.
	 *
	 * @param $destination
	 *
	 * @return $this
	 * @throws \Webiny\StdLib\StdObject\StdObjectException
	 */
	function move($destination) {
		try {
			$this->copy($destination);
			$this->delete();
		} catch (\ErrorException $e) {
			throw new StdObjectException('FileObject: Unable to move the given file: "' . $this->val() . '"
			to destination "' . $destination . '"');
		}

		return $this;
	}

	/**
	 * Copy the file to given destination.
	 *
	 * @param $destination
	 *
	 * @return $this
	 * @throws \Webiny\StdLib\StdObject\StdObjectException
	 */
	function copy($destination) {
		try {
			copy($this->val(), $destination);
		} catch (\ErrorException $e) {
			throw new StdObjectException('FileObject: Unable to copy the given file: "' . $this->val() . '"
			to destination "' . $destination . '"');
		}

		return $this;
	}

	/**
	 * Rename current file.
	 *
	 * @param $name
	 *
	 * @return $this
	 * @throws \Webiny\StdLib\StdObject\StdObjectException
	 */
	function rename($name){
		try {
			rename($this->val(), $name);
		} catch (\ErrorException $e) {
			throw new StdObjectException('FileObject: Unable to rename the given file: "' . $this->val() . '"
			to "' . $name. '"');
		}

		return $this;
	}

	/**
	 *  Sets access and modification time of file.
	 *
	 * @param null $time
	 *
	 * @return $this
	 * @throws \Webiny\StdLib\StdObject\StdObjectException
	 */
	function touch($time=null) {
		try {
			if(is_null($time)){
				touch($this->valid());
			}else{
				touch($this->valid(), $time);
			}
		} catch (\ErrorException $e) {
			throw new StdObjectException('FileObject: Unable to perform touch on the given file: "' . $this->val() . '".');
		}

		return $this;
	}

	function val(){
		return $this->_filePath;
	}
}