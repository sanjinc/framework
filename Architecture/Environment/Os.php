<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 * @package   WebinyFramework
 */

namespace WF\Architecture\Environment;

/**
 * Operating system environment class.
 *
 * @package         WebinyFramework
 * @category        Architecture
 * @subcategory        Environment
 */

class Os
{
    use \WF\StdLib\StdLib,
        \WF\StdLib\Singleton;

    private $_osName;

    /**
     * Detects and returns operating system name.
     *
     * @return string;
     */
    function getOsName() {
        if(!$this->isNull($this->_osName)) {
            return $this->_osName;
        }

        $os = $this->str(PHP_OS)->upper();
        ;
        if($os->subString(0, 3)->equals('WIN')) {
            $this->_osName = 'Windows';
        } else {
            if($os->contains('LINUX')) {
                $this->_osName = 'Linux';
            } else {
                if($os->contains('DARWIN')) {
                    $this->_osName = 'Darwin';
                } else {
                    if($os->contains('FREEBSD')) {
                        $this->_osName = 'FreeBSD';
                    } else {
                        $this->_osName = 'Other';
                    }
                }
            }
        }

        return $this->_osName;
    }

    /**
     * Returns directory separator based on the current OS.
     * @return string
     */
    function getDirectorySeparator() {
        return DIRECTORY_SEPARATOR;
    }


    /**
     * Checks if current OS is Windows.
     * @return bool
     */
    public function isWindows() {
        return ($this->_osName == 'Windows');
    }

    /**
     * Checks if current OS is Linux.
     * @return bool
     */
    public function isLinux() {
        return ($this->_osName == 'Linux');
    }

    /**
     * Checks if current OS is Darwin.
     * @return bool
     */
    public function isDarwin() {
        return ($this->_osName == 'Darwin');
    }

    /**
     * Checks if current OS is FreeBSD.
     * @return bool
     */
    public function isFreeBsd() {
        return ($this->_osName == 'FreeBSD');
    }

    public function isOther() {
        return ($this->_osName == 'Other');
    }

    /**
     * Singleton trait.
     * NOTE: This function must be declared static.
     * This function must return:
     * self::_getInstance();
     *
     * @return $this
     */
    static function getInstance() {
        return self::_getInstance();
    }
}