<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Config\Drivers;

use Webiny\Bridge\Yaml\Yaml;
use Webiny\Bridge\Yaml\YamlAbstract;
use Webiny\Bridge\Yaml\YamlInterface;
use Webiny\Component\Config\ConfigException;
use Webiny\StdLib\StdObject\FileObject\FileObject;
use Webiny\StdLib\StdObject\StdObjectWrapper;
use Webiny\StdLib\StdObject\StringObject\StringObject;
use Webiny\StdLib\ValidatorTrait;

/**
 * YamlDriver is responsible for parsing Yaml config files and returning a config array.
 *
 * @package   Webiny\Component\Config\Drivers;
 */

class YamlDriver extends DriverAbstract
{
	private $_indent = 4;
	/**
	 * @var null|YamlInterface
	 */
	private $_yaml = null;

	public function __construct($resource = null) {
		parent::__construct($resource);
		$this->_yaml = Yaml::getInstance();
	}

	/**
	 * Set Yaml indent
	 *
	 * @param int $indent
	 *
	 * @throws ConfigException
	 * @return $this
	 */
	public function setIndent($indent) {
		if(!$this->isNumber($indent)) {
			throw new ConfigException(ConfigException::MSG_INVALID_ARG, [
																		'$indent',
																		'integer'
																		]);
		}
		$this->_indent = $indent;

		return $this;
	}

	/**
	 * Get config as Yaml string
	 *
	 * @return string
	 */
	protected function _getString() {
		return $this->_yaml->setResource($this->_resource)->getString($this->_indent);
	}

	/**
	 * Parse config resource and build config array
	 *
	 * @throws ConfigException
	 * @return array Config data array
	 */
	protected function _getArray() {
		try {
			return $this->_yaml->setResource($this->_resource)->getArray();
		} catch (Exception $e) {
			throw new ConfigException($e->getMessage());
		}
	}
}