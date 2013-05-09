<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright @ 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Config;

use Webiny\Component\Config\Drivers\DriverAbstract;
use Webiny\StdLib\StdLibTrait;
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
    use StdLibTrait;

    /**
     * Config data
     *
     * @var array
     */
    protected $_data = array();

    /**
     * Get Config object from INI file or string
     *
     * @param string $resource      Config resource in form of a file path or config string
     *
     * @param bool   $flushCache    Flush existing cache and load config file
     *
     * @param string $nestDelimiter Delimiter for nested properties, ex: a.b.c or a-b-c
     *
     * @return $this
     */
    public static function Ini($resource, $flushCache = false, $nestDelimiter = '.')
    {
        $driver = new Drivers\IniDriver($resource);
        $driver->setDelimiter($nestDelimiter);

        return new static($driver, $flushCache);

    }

    /**
     * Get Config object from JSON file or string
     *
     * @param string $resource      Config resource in form of a file path or config string
     *
     * @param bool   $flushCache    Flush existing cache and load config file
     *
     * @return $this
     */
    public static function Json($resource, $flushCache = false)
    {
        $driver = new Drivers\JsonDriver($resource);

        return new static($driver, $flushCache);

    }


    /**
     * Get Config object from PHP file or array
     *
     * @param string|array $resource      Config resource in form of a file path or config string
     *
     * @param bool   $flushCache    Flush existing cache and load config file
     *
     * @return $this
     */
    public static function Php($resource, $flushCache = false)
    {
        $driver = new Drivers\PhpDriver($resource);

        return new static($driver, $flushCache);
    }

    /**
     * Parse resource and create a Config object
     * A valid resource is a PHP array, ArrayObject or an instance of DriverAbstract
     *
     * @param array|ArrayObject|DriverAbstract $resource   Config resource
     * @param bool                             $flushCache Flush existing cache and load config file
     *
     * @return static
     */
    public static function parseResource($resource, $flushCache = false)
    {
        return new static($resource, $flushCache);
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
     * Config is an object representing config data
     *
     * @param  array|ArrayObject|DriverAbstract $resource Config resource
     *
     * @param bool                              $flushCache Flush existing cache and load config file
     *
     * @throws ConfigException
     * @internal param $
     *
     */
    private function __construct($resource, $flushCache = false)
    {
        $driverAbstractClassName = '\Webiny\Component\Config\Drivers\DriverAbstract';
        $arrayObjectClassName = '\Webiny\StdLib\StdObject\ArrayObject\ArrayObject';

        // Validate given resources
        if (!$this->isArray($resource) && !$this->isInstanceOf($resource, $driverAbstractClassName
        ) && !$this->isArrayObject($resource)
        ) {
            throw new ConfigException("Config resource must be a valid array, $arrayObjectClassName or $driverAbstractClassName");
        }

        // If it's a DriverAbstract class - get config array
        if ($this->isInstanceOf($resource, $driverAbstractClassName)) {
            $resource = $resource->getConfig($flushCache);
        }

        $array = StdObjectWrapper::toArray($resource);

        $this->_data = $this->arr();
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