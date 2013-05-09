<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Config\Drivers;

use Webiny\Component\Config\ConfigException;
use Webiny\StdLib\StdLibTrait;
use Webiny\StdLib\StdObject\ArrayObject\ArrayObject;
use Webiny\StdLib\StdObject\StringObject\StringObject;
use Webiny\StdLib\ValidatorTrait;

/**
 * Abstract Driver class
 *
 * @package   Webiny\Component\Config\Drivers;
 */
abstract class DriverAbstract
{
    use StdLibTrait;

    private static $_configCache = null;
    /**
     * Contains config data which needs to be parsed and converted to Config object
     * @var null|string|array Resource given to config driver
     */
    protected $_resource = null;

    /**
     * Validate given config resource and throw ConfigException if it's not valid
     * @throws ConfigException
     */
    abstract protected function _validateResource();

    /**
     * Parse config resource and build config array
     * @return array|ArrayObject
     */
    abstract protected function _buildConfig();

    public function __construct($resource)
    {
        if (self::isNull(self::$_configCache)) {
            self::$_configCache = $this->arr();
        }
        $this->_resource = $resource;
        $this->_validateResource();
    }

    /**
     * Get config data as array
     *
     * @param bool $flushCache
     *
     * @return array|ArrayObject
     */
    public function getConfig($flushCache = false)
    {
        $res = $this->str($this->_resource)->md5()->val();
        if ($flushCache) {
            return self::$_configCache->key($res, $this->_buildConfig());
        } else {
            if (!self::$_configCache->keyExists($res)) {
                self::$_configCache->key($res, $this->_buildConfig());
            }
            return self::$_configCache->key($res);
        }
    }
}