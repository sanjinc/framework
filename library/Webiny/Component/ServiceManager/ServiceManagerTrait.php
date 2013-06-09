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
	 *
	 * @param string $serviceName
	 * @param null   $arguments
	 *
	 * @return object
	 */
	protected static function getService($serviceName, $arguments = null) {
		return ServiceManager::getInstance()->getService($serviceName, $arguments);
	}
}