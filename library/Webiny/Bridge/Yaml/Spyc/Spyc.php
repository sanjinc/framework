<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Bridge\Yaml\Spyc;

use use Webiny\Bridge\Yaml\YamlAbstract;
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
class Spyc extends YamlAbstract
{
	use StdLibTrait;

	private $_indent = null;
	private $_wordWrap = null;

	public function __construct($indent = 2, $wordWrap = false) {
		$this->_indent = $indent;
		$this->_wordWrap = $wordWrap;
	}

	/**
	 * Get current Yaml value as string
	 *
	 * @return string
	 */
	function getStringValue() {
		return $this->_toString();
	}

	/**
	 * Get Yaml value as array
	 *
	 * @return array
	 */
	function getArrayValue() {
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
	 * @return $this
	 */
	private function _parseResource() {
		if($this->isFile($this->_resource)) {
			return \Spyc\Spyc::YAMLLoad($this->_resource);
		} else {
			return \Spyc\Spyc::YAMLLoadString($this->_resource);
		}
	}

	/**
	 * Convert given data to Yaml string
	 *
	 * @throws SpycException
	 * @return $this
	 */
	private function _toString() {
		$data = $this->_resource;
		if(!$this->isArray($data) && !$this->isArrayObject($data) && !$this->isInstanceOf($data,
																						  'Webiny\Component\Config\Config')
		) {
			throw new SpycException('Spyc Bridge - Invalid argument supplied. Argument must be a valid array, ArrayObject or Webiny\Component\Config\Config');
		}

		if($this->isInstanceOf($data, 'Webiny\Component\Config\Config')) {
			$data = $data->toArray();
		}
		$data = StdObjectWrapper::toArray($data);

		return \Spyc\Spyc::YAMLDump($data, $this->_indent, $this->_wordWrap);
	}
}