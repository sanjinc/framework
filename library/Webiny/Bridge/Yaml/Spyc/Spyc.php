<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Bridge\Yaml\Spyc;

use Webiny\Bridge\Yaml\YamlAbstract;
use Webiny\Bridge\Yaml\YamlInterface;
use Webiny\Component\Config\Config;
use Webiny\StdLib\StdLibTrait;
use Webiny\StdLib\StdObject\FileObject\FileObject;
use Webiny\StdLib\StdObject\StdObjectAbstract;
use Webiny\StdLib\StdObject\StdObjectException;
use Webiny\StdLib\StdObject\StdObjectWrapper;
use Webiny\StdLib\StdObject\StringObject\StringObject;
use Webiny\StdLib\ValidatorTrait;

/**
 * Bridge for Spyc Yaml parser
 *
 * @package   Webiny\Bridge\Yaml\Spyc
 */
class Spyc implements YamlInterface
{
	use StdLibTrait;

	private $_indent = null;
	private $_wordWrap = null;
	private $_resource = null;

	/**
	 * Set resource to work on
	 * @param $resource
	 *
	 * @return $this
	 */
	public function setResource($resource) {
		$this->_resource = $resource;
		return $this;
	}

	/**
	 * Get Yaml value as string
	 *
	 * @param int  $indent
	 * @param bool $wordWrap
	 *
	 * @return string Yaml string
	 */
	function getString($indent = 2, $wordWrap = false) {
		$this->_indent = $indent;
		$this->_wordWrap = $wordWrap;

		return $this->_toString();
	}

	/**
	 * Get Yaml value as array
	 *
	 * @return array Parsed Yaml array
	 */
	function getArray() {
		return $this->_parseResource();
	}

	/**
	 * Write current Yaml data to file
	 *
	 * @param string|StringObject|FileObject $destination
	 *
	 * @throws SpycException
	 * @return bool
	 */
	public function writeToFile($destination) {

		if(!$this->isString($destination) && !$this->isStringObject($destination) && !$this->isFileObject($destination)) {
			throw new SpycException('Spyc Bridge - destination argument must be a string, StringObject or FileObject!');
		}

		try {
			$destination = $this->file($destination);
			$destination->truncate()->write($this->_toString());
		} catch (StdObjectException $e) {
			throw new SpycException('Spyc Bridge - ' . $e->getMessage());
		}

		return true;
	}

	/**
	 * Parse given Yaml resource and build array
	 * This method must support file paths (string or StringObject) and FileObject
	 *
	 * @throws SpycException
	 * @return string
	 */
	private function _parseResource() {
		if($this->isArray($this->_resource) || $this->isArrayObject($this->_resource)) {
			return StdObjectWrapper::toArray($this->_resource);
		} elseif($this->isFileObject($this->_resource)) {
			return \Spyc\Spyc::YAMLLoad($this->_resource->val());
		} elseif($this->isFile($this->_resource)) {
			return \Spyc\Spyc::YAMLLoad($this->_resource);
		} elseif($this->isString($this->_resource) || $this->isStringObject($this->_resource)) {
			return \Spyc\Spyc::YAMLLoadString(StdObjectWrapper::toString($this->_resource));
		} elseif($this->isInstanceOf($this->_resource, 'Webiny\Component\Config\ConfigObject')) {
			return $this->_resource->toArray();
		}

		throw new SpycException('Spyc Bridge - Unable to parse given resource of type %s', [gettype($this->_resource)]);
	}

	/**
	 * Convert given data to Yaml string
	 *
	 * @throws SpycException
	 * @return $this
	 */
	private function _toString() {
		$data = $this->_parseResource();

		return \Spyc\Spyc::YAMLDump($data, $this->_indent, $this->_wordWrap);
	}

}