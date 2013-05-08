<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright @ 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Config;

use Webiny\StdLib\StdObject\ArrayObject\ArrayObject;
use Webiny\StdLib\StdObject\StdObjectWrapper;
use Webiny\StdLib\ValidatorTrait;

/**
 * Config class creates config objects from files, strings and arrays.
 *
 * Example usage:
 * $config = \Webiny\Components\Config\Config::Ini('path/to/file.ini');
 *
 * @package         Webiny\Component\Config
 */
class Config
{
    use ValidatorTrait;

    /**
     * Config data
     *
     * @var array
     */
    protected $_data = array();

    /**
     * @param string $resource      Config resource in form of a file path or config string
     *
     * @param string $nestDelimiter Delimiter for nested properties, ex: a.b.c or a-b-c
     *
     * @return $this
     */
    public static function Ini($resource, $nestDelimiter = '.')
    {
        $driver = new Drivers\IniDriver($resource);
        $driver->setDelimiter($nestDelimiter);

        return new static($driver->getConfig());

    }

    /**
     * @param string $resource      Config resource in form of a file path or config string
     *
     * @throws ConfigException
     *
     * @return $this
     */
    public static function Php($resource)
    {
        if(!self::isArray($resource)){
            throw new ConfigException('PHP Config resource must be a valid array.');
        }
        return new static($resource);
    }

    /**
     * Get value or return $default if there is no element set.
     *
     * @param  string $name
     * @param  mixed  $default
     *
     * @return mixed
     */
    public function get($name, $default = null)
    {
        if ($this->_data->keyExists($name)) {
            return $this->_data->key($name);
        }

        return $default;
    }

    /**
     * Constructor.
     *
     * @param  array $array|ArrayObject
     */
    public function __construct($array)
    {
        $array = StdObjectWrapper::toArray($array);
        $this->_data = new ArrayObject();
        foreach ($array as $key => $value) {
            if ($this->isArray($value)) {
                $this->_data->key($key, new static($value));
            } else {
                $this->_data->key($key, $value);
            }
        }
    }

    /**
     * Access internal data as if it was a real object
     *
     * @param  string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * Set internal data as if it was a real object
     *
     * @param  string $name
     * @param  mixed  $value
     *
     * @return void
     */
    public function __set($name, $value)
    {
        if ($this->isArray($value)) {
            $value = new static($value);
        }

        if ($this->isNull($name)) {
            $this->_data[] = $value;
        } else {
            $this->_data[$name] = $value;
        }
    }

}