<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Config\Drivers;

use Webiny\Bridge\Yaml\Yaml as YamlBridge;
use Webiny\Component\Config\ConfigException;
use Webiny\StdLib\StdObject\FileObject\FileObject;
use Webiny\StdLib\StdObject\StdObjectException;
use Webiny\StdLib\StdObject\StdObjectWrapper;
use Webiny\StdLib\StdObject\StringObject\StringObject;
use Webiny\StdLib\ValidatorTrait;

/**
 * YamlDriver is responsible for parsing Yaml config files and returning a config array.
 *
 * @package   Webiny\Component\Config\Drivers;
 */

class YamlAbstract extends DriverAbstract
{
	/**
	 * @var null|\Webiny\Bridge\Yaml\Yaml
	 */
	private $_yaml = null;

	public function __construct($resource = null) {
		parent::__construct($resource);
		$this->_yaml = new YamlBridge();
	}

	/**
	 * Convert given data to appropriate string format
	 *
	 * @param      $data
	 *
	 * @param bool $indent
	 * @param bool $wordwrap
	 *
	 * @return string
	 */
	public function toString($data, $indent = false, $wordwrap = false) {
		return $this->_yaml->toString($data, $indent, $wordwrap)->getStringValue();
	}

	/**
	 * Save given data to given destination
	 *
	 * @param $data
	 * @param $destination
	 *
	 * @return mixed
	 */
	protected function _saveToFile($data, $destination) {
		return $this->_yaml->writeToFile($data, $destination);
	}

	/**
	 * Parse config resource and build config array
	 * @return array
	 */
	protected function _buildArray() {
		return $this->_parseYamlString($this->_resource);
	}

	/**
	 * Validate given config resource and throw ConfigException if it's not valid
	 * @throws ConfigException
	 */
	protected function _validateResource() {
		if(self::isNull($this->_resource)) {
			throw new ConfigException('Config resource can not be NULL! Please provide a valid file path, config string or PHP array.');
		}

		// Perform string checks
		try {
			$this->_resource = $this->str($this->_resource);
			if($this->_resource->trim()->length() == 0) {
				throw new ConfigException('Config resource string can not be empty! Please provide a valid file path, config string or PHP array.');
			}
		} catch (StdObjectException $e) {
			throw new ConfigException($e->getMessage());
		}
	}

	private function _parseYamlString($data) {
		$data = StdObjectWrapper::toString($data);
		$yaml = new YamlBridge();

		return $yaml->parseYaml($data)->val();
	}
}