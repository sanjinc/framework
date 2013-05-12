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
use Webiny\StdLib\StdObject\FileObject\FileObject;
use Webiny\StdLib\StdObject\StdObjectWrapper;
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

	/**
	 * Contains config data which needs to be parsed and converted to ConfigObject
	 * @var null|string|array Resource given to config driver
	 */
	protected $_resource = null;

	/**
	 * Convert given data to appropriate string format
	 *
	 * @param $data
	 *
	 * @return string
	 */
	abstract public function toString($data);

	/**
	 * Parse config resource and build config array
	 * @return array|ArrayObject
	 */
	abstract protected function _buildArray();

	/**
	 * Save given data to given destination
	 *
	 * @param $data
	 * @param $destination
	 *
	 * @return mixed
	 */
	abstract protected function _saveToFile($data, $destination);

	/**
	 * Validate given config resource and throw ConfigException if it's not valid
	 * @throws ConfigException
	 */
	abstract protected function _validateResource();

	public function __construct($resource = null) {

		if($this->isArray($resource) || $this->isArrayObject($resource)) {
			$this->_resource = StdObjectWrapper::toArray($resource);
		} else {
			$this->_resource = $resource;
		}
	}

	/**
	 * Get config data as array
	 *
	 * @return array|ArrayObject
	 */
	final public function getArray() {
		$this->_validateResource();

		return $this->_buildArray();
	}

	final public function getResource() {
		return $this->_resource;
	}

	final public function setResource($resource) {
		$this->_resource = $resource;
	}

	/**
	 * Write config to destination
	 *
	 * @param                                     $data
	 * @param null|string|StringObject|FileObject $destination
	 *
	 * @throws \InvalidArgumentException
	 * @throws \Webiny\Component\Config\ConfigException
	 * @return mixed
	 */
	final public function saveToFile($data, $destination = null) {

		if($this->isString($destination) || $this->isStringObject($destination)) {
			$destination = StdObjectWrapper::toString($destination);
		}

		if(!$this->isNull($destination)) {
			try {
				$destination = $this->file($destination);
			} catch (StdObjectException $e) {
				throw new \InvalidArgumentException('Invalid $destination argument! ' . $e->getMessage());
			}
		} else {
			if($this->isNull($this->_resource)) {
				throw new ConfigException('No valid resource was found to use as config target file! Specify a $destination argument or load your Config using a file resource!');
			}
			$destination = $this->_resource;
		}


		return $this->_saveToFile($this->toString($data), $destination);
	}
}