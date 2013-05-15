<?php

namespace Webiny\Component\Logger;

use Psr\Log\InvalidArgumentException;
use Webiny\StdLib\StdLibTrait;
use Webiny\StdLib\StdObject\StdObjectWrapper;

/**
 * Webiny Logger
 *
 */
class Logger
{
	use StdLibTrait;

	private static $_driver = 'Webiny\Component\Logger\Drivers\Monolog';

	private static $_loggerInstances = [];

	private static $_driverClass = 'Webiny\Component\Logger\LoggerAbstract';

	public static function getInstance($channelName){

		if(!self::isString($channelName) && !self::isStringObject($channelName)){
			throw new LoggerException(LoggerException::MSG_INVALID_ARG, ['$channelName', 'string or StringObject']);
		}
		$channelName = StdObjectWrapper::toString($channelName);

		self::$_loggerInstances = self::arr(self::$_loggerInstances);

		if(self::$_loggerInstances->keyExists($channelName)){
			return self::$_loggerInstances->key($channelName);
		}

		$loggerInstance = call_user_func(array(self::$_driver, 'getInstance'), array($channelName));

		if(!self::isInstanceof($loggerInstance, self::$_driverClass)){
			throw new LoggerException('Logger driver must be an instance of '.self::$_driverClass);
		}

		self::$_loggerInstances->key($channelName, $loggerInstance);
		return $loggerInstance;
	}

	public static function setDriver($driverClass){
		if(!self::isString($driverClass) && !self::isStringObject($driverClass)){
			throw new LoggerException(LoggerException::MSG_INVALID_ARG, ['$driverClass', 'string or StringObject']);
		}

		// Validate driver class
		if(!class_exists($driverClass)){
			throw new LoggerException("Provided Logger driver class '".$driverClass."' does not exist!");
		}

		self::$_driver = $driverClass;
	}
}
