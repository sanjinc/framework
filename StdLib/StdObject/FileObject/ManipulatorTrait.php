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
     * @return \SplFileObject
     */
    abstract protected function _getHandler();

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
                if(!file_exists(dirname($this->_path))) {
                    mkdir(dirname($this->_path), 0777, true);
                }

                // check the string length
                if(!$this->isNull($length)) {
                    $str = new StringObject($str);
                    $str->subString(0, $length);
                }

                // create and save the file
                file_put_contents($this->_path, $str);

                // update status
                $this->_fileExists = true;
            } else {
                if($this->isNull($length)){
                    $this->_getHandler()->fwrite($str);
                }else{
                    $this->_getHandler()->fwrite($str, $length);
                }

            }
        } catch (\Exception $e) {
            $this->exception('FileObject: Unable to write to file "' . $this->_path . '".' . "\n " . $e->getMessage());
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
            $this->_getHandler()->ftruncate($size);
        } catch (\Exception $e) {
            throw $this->exception('FileObject: Unable to truncate the data from the given file: ' . $this->_path .
                                       "\n " . $e->getMessage());
        }

        return $this;
    }

    /**
     * Perform chmod on current file.
     *
     * @param int $mode Must be in octal format. Example 0744
     *
     * @return $this
     * @throws StdObjectException
     */
    function chmod($mode = 0644) {
        if(!$this->_fileExists) {
            throw $this->exception('FileObject: Unable to perform chmod because the file doesn\'t exist: ' . $this->_path);
        }

        // few $mode checks
        $modCheck = new StringObject($mode);
        if($modCheck->length() != 4) {
            throw $this->exception('FileObject: The chmod $mode param must be exactly 4 chars.');
        }
        if(!$modCheck->startsWith('0')) {
            throw $this->exception('FileObject: The chmod $mode param must start with zero (0).');
        }
        $modeChunks = $modCheck->split();
        if($modeChunks->key(1) > 7 || $modeChunks->key(2) > 7 || $modeChunks->key(3) > 7) {
            throw $this->exception('FileObject: Invalid chmod $mode value.');
        }

        try {
            chmod($this->_path, $mode);
        } catch (\Exception $e) {
            throw $this->exception('FileObject: Unable to perform chmod on: ' . $this->_path .
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
    function delete(){
        try{
            unlink($this->_path);
        }catch (\ErrorException $e){
            throw $this->exception('FileObject: Unable to delete the given file: '.$this->_path);
        }

        return $this;
    }
}