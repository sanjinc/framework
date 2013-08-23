<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright @ 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Storage;

use Webiny\Component\ServiceManager\ServiceManager;

/**
 * A library of Storage functions
 *
 * @package Webiny\Component\Storage
 */
trait StorageTrait
{
	/**
	 * Get storage
	 *
	 * @param string $storageName Storage name
	 *
	 * @return Storage
	 */
	protected static function storage($storageName) {
		return ServiceManager::getInstance()->getService('storage.' . $storageName);
	}
}