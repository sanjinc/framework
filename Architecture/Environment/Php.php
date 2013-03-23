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
 * Reads data about current PHP version.
 *
 * @package         WebinyFramework
 * @category        Architecture
 * @subcategory        Environment
 */

class Php
{
    use \WF\StdLib\Singleton,
        \WF\StdLib\StdLib;

    private $_phpVersion;
    private $_magicQuotes;

    /**
     * Returns info about current version of PHP.
     *
     * @return string
     */
    function getVersion() {
        $this->_phpVersion = ($this->isNull($this->_phpVersion)) ? phpversion() : $this->_phpVersion;

        return $this->_phpVersion;
    }

    /**
     * Checks if current PHP version is equal or larger than $versionToCompare.
     *
     * @param string $versionToCompare  For example 5.4.11
     *
     * @return mixed
     */
    function isVersionValid($versionToCompare) {
        return version_compare($this->getVersion(), $versionToCompare);
    }

    /**
     * Returns status about magic quotes.
     *
     * @return int False is returned if magic quotes are turned off.
     */
    function checkMagicQuotes() {
        $this->_magicQuotes = ($this->isNull($this->_magicQuotes)) ? get_magic_quotes_gpc() : $this->_magicQuotes;

        return $this->_magicQuotes();
    }

    /**
     * Singleton trait.
     * NOTE: This function must be declared static.
     * This function must return:
     * self::_getInstance();
     *
     * @return Php
     */
    static function getInstance() {
        return self::_getInstance();
    }
}