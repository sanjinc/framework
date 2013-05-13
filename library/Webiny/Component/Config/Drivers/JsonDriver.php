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
use Webiny\StdLib\Exception\Exception;
use Webiny\StdLib\StdObject\StdObjectException;
use Webiny\StdLib\StdObject\StringObject\StringObject;
use Webiny\StdLib\ValidatorTrait;

/**
 * JsonDriver is responsible for parsing JSON config files and returning a config array.
 *
 * @package   Webiny\Component\Config\Drivers;
 */

class JsonDriver extends DriverAbstract
{

	/**
	 * Convert given data to appropriate string format
	 *
	 * @param $data
	 *
	 * @return string
	 */
	public function toString($data) {
		return json_encode($data);
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
		$this->file($destination)->write($data);

		return true;
	}

	/**
	 * Parse config resource and build config array
	 * @return array
	 */
	protected function _buildArray() {
		if(file_exists($this->_resource)) {
			$config = $this->file($this->_resource)->getFileContent();
		} else {
			$config = $this->_resource;
		}

		return $this->_parseJsonString($config);
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
			$this->_resource = $this->str($this->_resource)->trim();
			if($this->_resource->length() == 0) {
				throw new ConfigException('Config resource string can not be empty! Please provide a valid file path, config string or PHP array.');
			}
		} catch (StdObjectException $e) {
			throw new ConfigException($e->getMessage());
		}
	}

	/**
	 * Parse JSON string and return config array
	 *
	 * @param array $data
	 *
	 * @throws ConfigException
	 * @return array
	 */
	private function _parseJsonString($data) {
		try {
			$config = json_decode($data, true);
		} catch (Exception $e) {
			throw new ConfigException($e->getMessage());
		}

		return $config;
	}
}