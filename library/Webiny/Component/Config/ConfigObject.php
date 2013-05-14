<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright @ 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Config;

use Traversable;
use Webiny\Component\Config\Drivers\DriverAbstract;
use Webiny\Component\Config\Drivers\IniDriver;
use Webiny\Component\Config\Drivers\JsonDriver;
use Webiny\Component\Config\Drivers\PhpDriver;
use Webiny\Component\Config\Drivers\YamlDriver;
use Webiny\StdLib\StdLibTrait;
use Webiny\StdLib\StdObject\ArrayObject\ArrayObject;
use Webiny\StdLib\StdObject\FileObject\FileObject;
use Webiny\StdLib\StdObject\StdObjectWrapper;
use Webiny\StdLib\StdObject\StringObject\StringObject;
use Webiny\StdLib\ValidatorTrait;

/**
 * ConfigObject class holds config data in an OO way
 *
 * @package         Webiny\Component\Config
 */
class ConfigObject implements \ArrayAccess, \IteratorAggregate
{
	use StdLibTrait;

	const ARRAY_RESOURCE = 'array';
	const STRING_RESOURCE = 'string';
	const FILE_RESOURCE = 'file';

	/**
	 * Config data
	 *
	 * @var array
	 */
	protected $_data = array();

	/**
	 * Cache key used to store this object to ConfigCache
	 * @var string|null
	 */
	private $_cacheKey = null;

	/**
	 * File resource that was used to build this config data
	 * @var string|StringObject|FileObject|null
	 */
	private $_fileResource = null;

	/**
	 * @var null|string
	 */
	private $_resourceType = null;

	/**
	 * @var null|string
	 */
	private $_driverClass = null;


	public function saveAsYaml($destination, $indent = 2, $wordWrap = false) {
		$driver = new YamlDriver($this->toArray());
		$driver->setIndent($indent)->setWordWrap($wordWrap)->saveToFile($destination);

		return $this;
	}

	public function getAsYaml($indent = 2, $wordWrap = false) {
		$driver = new YamlDriver($this->toArray());

		return $driver->setIndent($indent)->setWordWrap($wordWrap)->getString();
	}

	public function saveAsPhp($destination) {
		$driver = new PhpDriver($this->toArray());
		$driver->saveToFile($destination);

		return $this;
	}

	public function getAsPhp() {
		$driver = new PhpDriver($this->toArray());

		return $driver->getString();
	}

	public function saveAsIni($destination) {

	}

	public function getAsIni() {
		$driver = new IniDriver($this->toArray());

		return $driver->getString();
	}

	public function saveAsJson($destination) {
		$driver = new JsonDriver($this->toArray());
		$driver->saveToFile($destination);

		return $this;

	}

	public function getAsJson() {
		$driver = new JsonDriver($this->toArray());

		return $driver->getString();
	}

	public function save() {
		if($this->_resourceType != ConfigObject::FILE_RESOURCE) {
			throw new ConfigException('ConfigObject was not created from a file resource and thus can not be saved directly!');
		}

		$driver = new $this->_driverClass($this->toArray());
		$driver->saveToFile($this->_fileResource);

		return $this;

	}

	/**
	 * Get value or return $default if there is no element set.
	 *
	 * @param  string $name
	 * @param  mixed  $default
	 *
	 * @return mixed
	 */
	public function get($name, $default = null) {
		if($this->_data->keyExists($name)) {
			return $this->_data->key($name);
		}

		return $default;
	}

	/**
	 * ConfigObject is an object representing config data in an OO way
	 *
	 * @param  array|ArrayObject|DriverAbstract $resource   Config resource
	 *
	 * @param bool                              $cache      Store ConfigObject to cache or not
	 *
	 * @throws ConfigException
	 */
	public function __construct($resource, $cache = true) {

		$driverAbstractClassName = '\Webiny\Component\Config\Drivers\DriverAbstract';
		$arrayObjectClassName = '\Webiny\StdLib\StdObject\ArrayObject\ArrayObject';

		// Validate given resources
		if(!$this->isArray($resource) && !$this->isInstanceOf($resource,
															  $driverAbstractClassName) && !$this->isArrayObject($resource)
		) {
			throw new ConfigException("ConfigObject resource must be a valid array, $arrayObjectClassName or $driverAbstractClassName");
		}


		if($this->isInstanceOf($resource, $driverAbstractClassName)) {
			$originalResource = $resource->getResource();
			// Store driver class name
			$this->_driverClass = get_class($resource);
			// Get driver to parse resource and return data array
			$resource = $resource->getArray();
		} else {
			$originalResource = $resource;
		}

		$this->_resourceType = self::determineResourceType($originalResource);
		if($this->_resourceType == self::FILE_RESOURCE) {
			$this->_fileResource = $originalResource;
		}

		// Make sure resource is an array
		$array = StdObjectWrapper::toArray($resource);

		// Build internal data array
		$this->_data = $this->arr();
		foreach ($array as $key => $value) {
			if($this->isArray($value)) {
				$this->_data->key($key, new static($value, false));
			} else {
				$this->_data->key($key, $value);
			}
		}

		// Store config to cache
		if($cache) {
			$this->_cacheKey = ConfigCache::createCacheKey($originalResource);
			ConfigCache::setCache($this->_cacheKey, $this);
		}
	}

	/**
	 * Get Config data in form of an array
	 * @return array
	 */
	public function toArray() {
		$data = [];
		foreach ($this->_data as $k => $v) {
			if($this->isInstanceOf($v, $this)) {
				$data[$k] = $v->toArray();
			} else {
				$data[$k] = $v;
			}
		}

		return $data;
	}

	/**
	 * Access internal data as if it was a real object
	 *
	 * @param  string $name
	 *
	 * @return mixed
	 */
	public function __get($name) {
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
	public function __set($name, $value) {
		if($this->isArray($value)) {
			$value = new static($value);
		}

		if($this->isNull($name)) {
			$this->_data[] = $value;
		} else {
			$this->_data[$name] = $value;
		}

		// Update cache with new value
		ConfigCache::setCache($this->_cacheKey, $this);
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Whether a offset exists
	 * @link http://php.net/manual/en/arrayaccess.offsetexists.php
	 *
	 * @param mixed $offset <p>
	 *                      An offset to check for.
	 * </p>
	 *
	 * @return boolean true on success or false on failure.
	 * </p>
	 * <p>
	 *       The return value will be casted to boolean if non-boolean was returned.
	 */
	public function offsetExists($offset) {
		return $this->_data->keyExists($offset);
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Offset to retrieve
	 * @link http://php.net/manual/en/arrayaccess.offsetget.php
	 *
	 * @param mixed $offset <p>
	 *                      The offset to retrieve.
	 * </p>
	 *
	 * @return mixed Can return all value types.
	 */
	public function offsetGet($offset) {
		return $this->_data->key($offset);
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Offset to set
	 * @link http://php.net/manual/en/arrayaccess.offsetset.php
	 *
	 * @param mixed $offset <p>
	 *                      The offset to assign the value to.
	 * </p>
	 * @param mixed $value  <p>
	 *                      The value to set.
	 * </p>
	 *
	 * @return void
	 */
	public function offsetSet($offset, $value) {
		$this->_data->key($offset, $value);
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Offset to unset
	 * @link http://php.net/manual/en/arrayaccess.offsetunset.php
	 *
	 * @param mixed $offset
	 * @param mixed $offset <p>
	 *                      The offset to unset.
	 * </p>
	 *
	 * @return void
	 */
	public function offsetUnset($offset) {
		$this->_data->removeKey($offset);
	}

	/**
	 * Override __isset
	 *
	 * @param  string $name
	 *
	 * @return bool
	 */
	public function __isset($name) {
		return $this->_data->keyExists($name);
	}

	/**
	 * Override __unset
	 *
	 * @param  string $name
	 *
	 * @return void
	 */
	public function __unset($name) {
		if($this->_data->keyExists($name)) {
			$this->_data->removeKey($name);
		}
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Retrieve an external iterator
	 * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
	 * @return Traversable An instance of an object implementing <b>Iterator</b> or
	 * <b>Traversable</b>
	 */
	public function getIterator() {
		return $this->_data->getIterator();
	}

	/**
	 * Determine type of given resource
	 *
	 * @param $resource
	 *
	 * @return string
	 * @throws ConfigException
	 */
	public static function determineResourceType($resource) {
		if(self::isArray($resource) || self::isArrayobject($resource)) {
			return self::ARRAY_RESOURCE;
		} elseif(self::isFile($resource) || self::isFileObject($resource)) {
			return self::FILE_RESOURCE;
		} elseif(self::isString($resource) || self::isStringobject($resource)) {
			return self::STRING_RESOURCE;
		}
		throw new ConfigException("Given ConfigObject resource is not allowed!");
	}
}