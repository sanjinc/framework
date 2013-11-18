<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Logger;

use Webiny\Bridge\Logger\LoggerDriverInterface;
use Webiny\Component\Logger\Drivers\Null;
use Webiny\Component\ServiceManager\ServiceManager;
use Webiny\Component\ServiceManager\ServiceManagerException;

/**
 * Logger trait.
 *
 * @package        Webiny\Component\Logger
 */

trait LoggerTrait
{

	/**
	 * Get logger.
	 * Just provide the logger name without the 'logger.' prefix.
	 * The name must match the name of your service.
	 *
	 * @param string $name Logger service name.
	 *
	 * @return LoggerDriverInterface
	 * @throws ServiceManagerException
	 */
	public static function logger($name) {
		try {
			return ServiceManager::getInstance()->getService('logger.'.$name);
		} catch (ServiceManagerException $e) {
			if($e->getCode() == ServiceManagerException::SERVICE_DEFINITION_NOT_FOUND) {
				return new Logger($name, new Null());
			}

			throw $e;
		}

	}

}