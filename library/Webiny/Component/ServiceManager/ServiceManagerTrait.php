<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright @ 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\ServiceManager;

/**
 * A library of ServiceManager functions
 *
 * @package         Webiny\Component\ServiceManager
 */
trait ServiceManagerTrait
{
	/**
	 * Get service
	 *
	 * @param string     $serviceName Service name
	 * @param null|array $arguments (Optional) Arguments for service constructor if you want to override default arguments
	 *
	 * @return object
	 */
	protected static function service($serviceName, $arguments = null) {
		return ServiceManager::getInstance()->getService($serviceName, $arguments);
	}
}