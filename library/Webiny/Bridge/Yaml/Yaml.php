<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Bridge\Yaml;

use Webiny\StdLib\Exception\ExceptionAbstract;
use Webiny\StdLib\StdLibTrait;
use Webiny\StdLib\StdObject\FileObject\FileObject;
use Webiny\StdLib\StdObject\StdObjectWrapper;
use Webiny\StdLib\StdObject\StringObject\StringObject;

/**
 * Bridge for Spyc Yaml parser
 *
 * @package   Webiny\Bridge\Yaml
 */
class Yaml implements YamlInterface
{
	use StdLibTrait;

	/**
	 * Default Yaml driver class name
	 * @var string
	 */
	private static $_driverClass = 'Spyc\Spyc';

	/**
	 * Instance of Yaml driver to use
	 * @var null|YamlInterface
	 */
	private static $_driverInstance = null;

	/**
	 * Driver interface to enforce
	 * @var string
	 */
	private static $_driverInterface = 'Webiny\Bridge\Yaml\YamlInterface';

	/**
	 * Set Yaml driver to use by Yaml bridge
	 *
	 * @param $driver string|YamlInterface
	 *
	 * @throws YamlException
	 */
	public static function setDriver($driver) {

		if(!self::isInstanceOf($driver, self::$_driverInterface)) {
			if(self::isString($driver) || self::isStringObject($driver)) {
				$driver = StdObjectWrapper::toString($driver);
				$driver = new $driver;
				if(self::isInstanceOf($driver, self::$_driverInterface)) {
					self::$_driverInstance = $driver;

					return;
				}
			}
			throw new YamlException(ExceptionAbstract::MSG_INVALID_ARG, [
																		'$driver',
																		self::$_driverInterface
																		]);
		}
		self::$_driverInstance = $driver;

		return;
	}

	public function __construct($resource=null, $indent = 2, $wordWrap = false) {
		if(!$this->isInstanceOf(self::$_driverInstance, self::$_driverInterface)) {
			self::$_driverInstance = new self::$_driverClass($indent, $wordWrap);
		}
		self::$_driverInstance->setResource($resource);
	}

	/**
	 * Write current Yaml data to file
	 *
	 * @param $data
	 * @param string|StringObject|FileObject $destination
	 *
	 * @throws YamlException
	 * @return bool
	 */
	function writeToFile($data, $destination) {
		$res = self::$_driverInstance->writeToFile($data, $destination);
		if(!$this->isBool($res)) {
			throw new YamlException('Yaml bridge method writeToFile() must return a boolean.');
		}

		return $res;
	}

	/**
	 * Get current Yaml value as string
	 *
	 * @throws YamlException
	 * @return string
	 */
	function getStringValue() {
		$res = self::$_driverInstance->getStringValue();
		if(!$this->isString($res) && !$this->isStringObject($res)) {
			throw new YamlException('Yaml bridge method getStringValue() must return a string.');
		}

		return StdObjectWrapper::toString($res);
	}

	/**
	 * Get Yaml value as array
	 *
	 * @throws YamlException
	 * @return array
	 */
	function getArrayValue() {
		$res = self::$_driverInstance->getArrayValue();
		if(!$this->isArray($res) && !$this->isArrayObject($res)) {
			throw new YamlException('Yaml bridge method getArrayValue() must return an array.');
		}

		return StdObjectWrapper::toString($res);
	}

	function setResource($resource){
		return self::$_driverInstance->setResource($resource);
	}

}