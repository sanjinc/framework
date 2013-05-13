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
	 * Get config data as string
	 *
	 * @return string
	 */
	abstract protected function _getString();

	/**
	 * Parse config resource and build config array
	 * @return array|ArrayObject
	 */
	abstract protected function _getArray();

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

	final public function getString() {
		$this->_validateResource();

		$res = $this->_getString();
		if(!$this->isString($res) && !$this->isStringObject($res)){
			throw new ConfigException('DriverAbstract method _getString() must return string or StringObject.');
		}
		return StdObjectWrapper::toString($res);
	}

	/**
	 * Get config data as array
	 *
	 * @throws \Webiny\Component\Config\ConfigException
	 * @return array|ArrayObject
	 */
	final public function getArray() {
		$this->_validateResource();

		$res = $this->_getArray();
		if(!$this->isArray($res) && !$this->isArrayObject($res)){
			throw new ConfigException('DriverAbstract method _getArray() must return array or ArrayObject.');
		}
		return StdObjectWrapper::toArray($res);
	}

	/**
	 * Get driver resource
	 * @return mixed Driver resource (can be: string, array, StringObject, ArrayObject, FileObject)
	 */
	final public function getResource() {
		return $this->_resource;
	}

	/**
	 * Set driver resource
	 * @param mixed $resource Driver resource (can be: string, array, StringObject, ArrayObject, FileObject)
	 *
	 * @return $this
	 */
	final public function setResource($resource) {
		$this->_resource = $resource;
		return $this;
	}

	/**
	 * Write config to destination
	 *
	 * @param null|string|StringObject|FileObject $destination
	 *
	 * @throws \InvalidArgumentException
	 * @throws \Webiny\Component\Config\ConfigException
	 * @return $this
	 */
	final public function saveToFile($destination = null) {

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

		try {
			$destination = $this->file($destination);
			$destination->truncate()->write($this->_getString());
			return $this;
		} catch (StdObjectException $e) {
			throw new ConfigException($e->getMessage());
		}
	}
}