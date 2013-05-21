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
use Webiny\StdLib\StdObject\FileObject\FileObject;
use Webiny\StdLib\StdObject\StdObjectException;
use Webiny\StdLib\StdObject\StdObjectWrapper;
use Webiny\StdLib\StdObject\StringObject\StringObject;
use Webiny\StdLib\ValidatorTrait;

/**
 * PhpDriver is responsible for parsing PHP config files and returning a config array.
 *
 * @package   Webiny\Component\Config\Drivers;
 */

class PhpDriver extends DriverAbstract
{
	/**
	 * Get config data as string
	 *
	 * @return string
	 */
	protected function _getString() {
		return "<?php\n" . "return " . var_export($this->_getArray(), true) . ";\n";
	}


	/**
	 * Parse config resource and build config array
	 * @return array
	 */
	protected function _getArray() {
		if($this->isArray($this->_resource)) {
			return $this->_resource;
		} else {
			return include $this->_resource;
		}
	}

	/**
	 * Validate given config resource and throw ConfigException if it's not valid
	 * @throws ConfigException
	 */
	protected function _validateResource() {
		// If array - it's a valid resource
		if($this->isArray($this->_resource)) {
			return true;
		}

		// If it's a string - make sure it's a valid file
		if($this->isString($this->_resource) && $this->isFile($this->_resource)) {
			return true;
		}

		throw new ConfigException('PHP Config resource must be a valid file path or PHP array.');
	}
}