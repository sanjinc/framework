<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 * @package   WebinyFramework
 */

namespace Webiny\Architecture;

/**
 * Description
 *
 * @package         WebinyFramework
 * @category        Architecture
 */

trait Environment
{
    /**
     * If found, returns $_GET[$name], else it returns $default.
     * No escaping is done on the fetched value.
     *
     * @param string $name        name of the param you want to get
     * @param mixed  $default     default value that will be returned if $name is not set inside $_GET
     *
     * @return mixed
     */
    public function get($name, &$default = false) {
        return \Webiny\Architecture\Environment\Request::getInstance()->getRequestParameter($name, 'get', $default);
    }

    /**
     * If found, returns $_POST[$name], else it returns $default.
     * No escaping is done on the fetched value.
     *
     * @param string $name        Name of the param you want to get.
     * @param mixed  $default     Default value that will be returned if $name is not set inside $_GET.
     *
     * @return mixed
     */
    public function post($name, &$default = false) {
        return \Webiny\Architecture\Environment\Request::getInstance()->getRequestParameter($name, 'post', $default);
    }

    /**
     * Returns the current web address, including the GET parameters.
     *
     * @param boolean $asUrlStandardObject If you want to return information about current web address in a form of an array.
     *
     * @return string|\Webiny\StdLib\StdObject\UrlObject\UrlObject
     */
    public function getCurrentUrl($asUrlStandardObject = false) {
        return \Webiny\Architecture\Environment\Request::getInstance()->getCurrentUrl($asUrlStandardObject);
    }

    /**
     * Returns information about if current connection is secured (https).
     *
     * @return boolean
     */
    public function isConnectionSecured() {
        return \Webiny\Architecture\Environment\Request::getInstance()->isConnectionSecured();
    }

    /**
     * Returns the IP address of the current visitor.
     *
     * @return string
     */
    public function getVisitorsIp() {
        return \Webiny\Architecture\Environment\Request::getInstance()->getVisitorsIp();
    }

    /**
     * Returns the current domain, without trailing slash.
     *
     * @return bool|string
     */
    public function getCurrentDomain() {
        return \Webiny\Architecture\Environment\Request::getInstance()->getCurrentDomain();
    }

    /**
     * Returns current PHP version.
     *
     * @return string
     */
    public function getPhpVersion() {
        return \Webiny\Architecture\Environment\Php::getInstance()->getVersion();
    }

    /**
     * Returns the name of current OS.
     * Possible values are: Windows, Linux, Darwin, FreeBSD, Other
     *
     * @return string
     */
    public function getOsName() {
        return \Webiny\Architecture\Environment\Os::getInstance()->getOsName();
    }

    /**
     * Returns directory separator for the current OS.
     *
     * @return string
     */
    public function getDirectorySeparator() {
        return \Webiny\Architecture\Environment\Os::getInstance()->getDirectorySeparator();
    }

    /**
     * Returns absolute path to WebinyFramework folder.
     *
     * @return string
     */
    public function getAbsPath() {
        return \Webiny\Architecture\Environment\Path::getInstance()->absPath();
    }
}