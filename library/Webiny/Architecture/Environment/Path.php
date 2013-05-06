<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 * @package   WebinyFramework
 */

namespace Webiny\Architecture\Environment;

/**
 * Description
 *
 * @package         WebinyFramework
 * @category        Architecture
 * @subcategory        Environment
 */

class Path
{
    use \Webiny\StdLib\Singleton,
        \Webiny\Architecture\Environment,
        \Webiny\StdLib\StdLib;

    private $_root;

    /**
     * Returns absolute path to WF
     *
     * @return string
     */
    public function absPath() {
        if($this->isNull($this->_root)) {
            $path = dirname(__FILE__) . $this->getDirectorySeparator() . '..' . $this->getDirectorySeparator() . '..' . $this->getDirectorySeparator();
            $path = realpath($path);
            if(!$path) {
                $this->exception('Unable to determine the current file path.');
            }

            $this->_root = $path;
        }

        return $this->_root;
    }


    /**
     * Singleton trait.
     * NOTE: This function must be declared static.
     * This function must return:
     * self::_getInstance();
     *
     * @return Path
     */
    static function getInstance() {
        return self::_getInstance();
    }
}