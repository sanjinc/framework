<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Http\Request\Files;

use Webiny\StdLib\StdLibTrait;
use Webiny\StdLib\StdObject\FileObject\FileObject;

/**
 * Description
 *
 * @package		 Webiny\Component\Http\Request\Files
 */
 
class File{

	use StdLibTrait;

	private $_name;
	private $_tmpName;
	private $_type;
	private $_error;
	private $_size;
	private $_stored = false;
	private $_storedPath = '';

	function __construct($name, $tmpName, $type, $error, $size){
		$this->_name = $name;
		$this->_tmpName = $tmpName;
		$this->_type = $type;
		$this->_error = $error;
		$this->_size = $size;
	}

	function getName(){
		return $this->_name;
	}

	function getTmpName(){
		return $this->_tmpName;
	}

	function getType(){
		return $this->_type;
	}

	function getError(){
		return $this->_error;
	}

	function getSize(){
		return $this->_size;
	}

	function store($filename, $folder){
		// check for errors
		if($this->getError()>0){
			throw new FilesException('Unable to move the file because an upload error occurred.');
		}


		// validate folder
		$folder = $this->str($folder);
		if(!$folder->endsWith('/') && !$folder->endsWith('\\')){
			$folder = $folder->val().DIRECTORY_SEPARATOR;
		}else{
			$folder->val();
		}

		// check if we have already stored the file
		$path = $folder.$filename;
		if($this->_stored && $this->_storedPath == $path){
			return true;
		}

		// move the file
		try{
			$result = move_uploaded_file($this->_tmpName, $path);
			$this->_stored = $result;
			$this->_storedPath = $folder.$filename;
		}catch (\Exception $e){
			throw new FilesException($e->getMessage());
		}

		return $result;
	}

	function asFileObject(){
		if(!$this->_storred){
			throw new FilesException('You must first store the file, using "store" method before you can use it as FileObject.');
		}

		try{
			return $this->file($this->_storedPath);
		}catch (\Exception $e){
			throw new FilesException($e->getMessage());
		}

	}
}