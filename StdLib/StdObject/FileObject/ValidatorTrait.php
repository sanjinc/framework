<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace WF\StdLib\StdObject\FileObject;

use WF\StdLib\StdObject\StdObjectValidatorTrait;

/**
 * File object validator trait.
 *
 * @package         WF\StdLib\StdObject\FileObject
 */
trait ValidatorTrait
{
    use StdObjectValidatorTrait;

    /**
     * @return \SplFileObject
     */
    abstract protected function _getHandler();

    /**
     * Does the file exist on the disk.
     *
     * @return bool
     */
    function exists() {
        return $this->_fileExists;
    }

    /**
     * Tells if the object references a regular file.
     *
     * @return bool
     */
    function isFile() {
        return $this->_getHandler()->isFile();
    }

    /**
     * Tells if the file is a link.
     *
     * @return bool
     */
    function isLink() {
        return $this->_getHandler()->isLink();
    }

    /**
     * Is file writable.
     *
     * @return bool
     */
    function isWritable(){
        return $this->_getHandler()->isWritable();
    }

    /**
     * If file readable.
     *
     * @return bool
     */
    function isReadable(){
        return $this->_getHandler()->isReadable();
    }
}