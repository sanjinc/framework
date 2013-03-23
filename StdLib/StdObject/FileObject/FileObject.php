<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace WF\StdLib\StdObject\FileObject;

use SplFileObject;
use WF\StdLib\StdObject\StdObjectAbstract;
use WF\StdLib\StdObject\StdObjectException;
use WF\StdLib\StdObject\StringObject\StringObject;
use WF\StdLib\ValidatorTrait;


/**
 * This is the file standard object.
 * Using this standard object you can check if file exits, you can also create and modify files and get file meta data.
 *
 * @package         WF\StdLib\StdObject
 */

class FileObject extends StdObjectAbstract
{

    use ValidatorTrait,
        ManipulatorTrait;

    private $_path = '';
    private $_fileExists = false;
    private $_handler = null;

    /**
     * Create a new file standard object.
     * It's not necessary that the file exists.
     *
     * @param string $pathToFile Absolute path to the file.
     */
    function __construct($pathToFile) {
        // assign file path
        $this->_path = $pathToFile;

        // correct directory separator
        $this->_correctDirectorySeparator();

        // check if file already exists
        $realPath = realpath($this->_path);
        if($realPath) {
            $this->_fileExists = true;
            $this->_path = $realPath;
        }
    }

    /**
     * Returns the file size in bytes.
     *
     * @throws StdObjectException
     * @return int
     */
    function getSize() {
        if(!$this->_fileExists) {
            throw $this->exception('FileObject: Unable to get file size because the file doesn\'t exist: ' . $this->_path);
        }

        try {
            return $this->_getHandler()->getSize();
        } catch (\Exception $e) {
            throw $this->exception('FileObject: Unable to get file size for file: ' . $this->_path . "\n " . $e->getMessage());
        }

    }

    /**
     * Returns file name without the directory path.
     *
     * @return mixed|string
     */
    function getBasename() {
        if(!$this->_fileExists) {
            $fileName = new StringObject($this->_path);

            return $fileName->explode(DIRECTORY_SEPARATOR)->last();
        }

        return $this->_getHandler()->getBasename();
    }

    /**
     * Return file extension.
     *
     * @return string
     */
    function getExtension() {
        if(!$this->_fileExists) {
            $fileName = new StringObject($this->_path);

            return strtolower($fileName->explode('.')->last());
        }

        return $this->_getHandler()->getExtension();
    }

    /**
     * Returns last modified time.
     *
     * @return int
     * @throws StdObjectException
     */
    function getMTime() {
        if(!$this->_fileExists) {
            throw $this->exception('FileObject: Unable to get last modified time because the file doesn\'t exist: '
                                       . $this->_path);
        }

        return $this->_getHandler()->getMTime();
    }

    function getPath() {
        if(!$this->_fileExists) {
            throw $this->exception('FileObject: Unable to get file path because the file doesn\'t exist: ' . $this->_path);
        }

        return $this->_getHandler()->getPath();
    }

    /**
     * Return current standard objects value.
     * Returns the path to the file.
     *
     * @return string
     */
    function getValue() {
        return $this->_path;
    }

    /**
     * Returns the current standard object instance.
     *
     * @return $this
     */
    function getObject() {
        return $this;
    }

    /**
     * The update value method is called after each modifier method.
     * It updates the current value of the standard object.
     *
     * @param mixed $value    Passed by reference.
     */
    function updateValue(&$value) {
        $this->_path = $value;
        $this->_handler = null;
    }

    /**
     * To string implementation.
     *
     * @return mixed
     */
    function __toString() {
        return $this->truncate();
    }

    /**
     * Get file handler.
     *
     * @throws StdObjectException
     * @return SplFileObject
     */
    protected function _getHandler() {
        if($this->isNull($this->_handler)) {
            try {
                $this->_handler = new SplFileObject($this->_path, 'w');
            } catch (\ErrorException $e) {
                throw $this->exception($this->_path . 'FileObject: Unable to open file handler.
									Please check that the file exists and that you have
									the necessary permissions for the location: "' . '"' .
                                           "\n " . $e->getMessage());
            }
        }

        return $this->_handler;
    }

    /**
     * Correct the directory separator and set it to OS default.
     */
    private function _correctDirectorySeparator() {
        $this->_path = str_replace(array(
                                        '/',
                                        '\\'
                                   ), DIRECTORY_SEPARATOR, $this->_path);
    }
}