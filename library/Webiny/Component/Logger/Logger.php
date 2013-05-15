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

	const DRIVER = 'Webiny\Component\Logger\Drivers\Monolog';

	private static $_driverClass = 'Webiny\Component\Logger\LoggerAbstract';

	public static function getInstance($channelName, $driverClass = Logger::DRIVER){
		// Validate $channelName
		if(!self::isString($channelName) && !self::isStringObject($channelName)){
			throw new LoggerException(LoggerException::MSG_INVALID_ARG, ['$channelName', 'string or StringObject']);
		}
		$channelName = StdObjectWrapper::toString($channelName);

		// Validate driver class
		if(!self::isString($driverClass) && !self::isStringObject($driverClass)){
			throw new LoggerException(LoggerException::MSG_INVALID_ARG, ['$driverClass', 'string or StringObject']);
		}

		if(!class_exists($driverClass)){
			throw new LoggerException("Provided Logger driver class '".$driverClass."' does not exist!");
		}

		// Get instance of logger
		$loggerInstance = call_user_func(array($driverClass, 'getInstance'), array($channelName));

		// Validate logger
		if(!self::isInstanceof($loggerInstance, self::$_driverClass)){
			throw new LoggerException('Logger driver must be an instance of '.self::$_driverClass);
		}

		return $loggerInstance;
	}
}
