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
use Webiny\StdLib\StdObject\StdObjectWrapper;
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
	 * Get config data as string
	 *
	 * @return string
	 */
	protected function _getString() {
		return json_encode($this->_getArray());
	}

	/**
	 * Parse config resource and build config array
	 * @return array|ArrayObject
	 */
	protected function _getArray() {
		if($this->isArray($this->_resource)) {
			return $this->_resource;
		}

		if(file_exists($this->_resource)) {
			$config = $this->file($this->_resource)->getFileContent();
		} else {
			$config = $this->_resource;
		}

		return $this->_parseJsonString($config);
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
			return json_decode($data, true);
		} catch (Exception $e) {
			throw new ConfigException($e->getMessage());
		}
	}
}