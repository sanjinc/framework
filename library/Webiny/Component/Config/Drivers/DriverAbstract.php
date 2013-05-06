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

/**
 * Abstract Driver class
 *
 * @package   Webiny\Component\Config\Drivers;
 */
abstract class DriverAbstract
{
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
     * @return array
     */
    abstract protected function _buildConfig();

    public function __construct($resource)
    {
        $this->_resource = $resource;
        $this->_validateResource();
    }

    /**
     * Get config data as array
     * @return array
     */
    public function getConfig()
    {
        return $this->_buildConfig();
    }
}