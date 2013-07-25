<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Crypt;

use Webiny\Component\ServiceManager\ServiceManager;

/**
 * Crypt trait.
 *
 * @package		 Webiny\Component\Crypt
 */
 
trait CryptTrait{

	/**
	 * Get Crypt component instance.
	 *
	 * @param string $cryptId Name of the crypt service.
	 *
	 * @throws \Exception|ServiceManagerException
	 * @return Crypt
	 */
	function crypt($cryptId = 'webiny_crypt'){
		try {
			return ServiceManager::getInstance()->getService('crypt.' . $cryptId);
		} catch (ServiceManagerException $e) {
			throw $e;
		}
	}
}