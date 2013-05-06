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
use Webiny\StdLib\StdObject\StdObjectException;
use Webiny\StdLib\StdObject\StringObject\StringObject;
use Webiny\StdLib\ValidatorTrait;

/**
 * Description
 *
 * @package   Webiny\Component\Config\Drivers;
 */

class IniDriver extends DriverAbstract
{
    use ValidatorTrait;

    private $_nestDelimiter = '.';

    /**
     * Set delimiting character for nested properties, ex: a.b.c or a-b-c
     *
     * @param $nestDelimiter
     */
    public function setDelimiter($nestDelimiter)
    {
        $this->_nestDelimiter = $nestDelimiter;
    }

    /**
     * @return array
     */
    protected function _buildConfig()
    {
        return [
            'name'    => 'Pavel',
            'email'   => 'pavel@webiny.com',
            'address' => [
                'city'    => 'Rijeka',
                'street'  => 'Labinska',
                'number'  => 47,
                'country' => 'Croatia'
            ]
        ];
    }

    /**
     * Validate given config resource and throw ConfigException if it's not valid
     * @throws ConfigException
     */
    protected function _validateResource()
    {
        if (self::isNull($this->_resource)) {
            throw new ConfigException('Config resource can not be NULL! Please provide a valid file path, config string or PHP array.');
        }

        // Perform string checks
        try {
            $this->_resource = new StringObject($this->_resource);
            if ($this->_resource->trim()->length() == 0) {
                throw new ConfigException('Config resource string can not be empty! Please provide a valid file path, config string or PHP array.');
            }
        } catch (StdObjectException $e) {
            throw new ConfigException($e->getMessage());
        }
    }

}