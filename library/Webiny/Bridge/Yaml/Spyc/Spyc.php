<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Bridge\Yaml\Spyc;

use Webiny\Component\Config\Config;
use Webiny\StdLib\StdLibTrait;
use Webiny\StdLib\StdObject\ArrayObject\ArrayObject;
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
class Spyc
{
	use StdLibTrait;

	private $_resource = null;

	/**
	 * Parse Yaml resource and create PHP array
	 *
	 * @param $resource
	 *
	 * @return $this
	 */
	public function parseYaml($resource) {
		if($this->isFile($resource)) {
			$this->_resource = \Spyc\Spyc::YAMLLoad($resource);
		} else {
			$this->_resource = \Spyc\Spyc::YAMLLoadString($resource);
		}

		return $this;
	}

	/**
	 * Convert data to Yaml
	 *
	 * @param array|ArrayObject|Config $data        Data in form of array, ArrayObject or Webiny\Component\Config\Config
	 * @param bool|int                 $indent      Pass in false to use the default, which is 2
	 * @param bool|int                 $wordwrap    Pass in 0 for no wordwrap, false for default (40)
	 *
	 * @throws SpycException
	 * @return $this
	 */
	public function createYaml($data, $indent = false, $wordwrap = false) {
		if(!$this->isArray($data) && !$this->isArrayObject($data) && !$this->isInstanceOf($data,
																						  'Webiny\Component\Config\Config')
		) {
			throw new SpycException('Spyc Bridge - Invalid argument supplied. Argument must be a valid array, ArrayObject or Webiny\Component\Config\Config');
		}

		if($this->isInstanceOf($data, 'Webiny\Component\Config\Config')) {
			$data = $data->toArray();
		}
		$data = StdObjectWrapper::toArray($data);
		$this->_resource = \Spyc\Spyc::YAMLDump($data, $indent, $wordwrap);

		return $this;
	}

	/**
	 * Get current Yaml data
	 * @return null|string
	 */
	public function val() {
		return $this->_resource;
	}

	/**
	 * Store current Yaml data to file
	 *
	 * @param string|StringObject|FileObject $destination Destination file
	 *
	 * @return $this
	 * @throws SpycException
	 */
	public function saveToFile($destination) {
		if($this->isNull($this->_resource)) {
			throw new SpycException('Spyc Bridge - can not store a NULL resource!');
		}

		if(!$this->isString($destination) && !$this->isStringObject($destination) && !$this->isFileObject($destination)) {
			throw new SpycException('Spyc Bridge - destination argument must be a string, StringObject or FileObject!');
		}

		try {
			$destination = $this->file($destination);
			$destination->truncate()->write($this->_resource);
		} catch (StdObjectException $e) {
			throw new SpycException('Spyc Bridge - ' . $e->getMessage());
		}

		return true;
	}
}