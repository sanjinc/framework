<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Logger\Drivers;

use Webiny\Bridge\Logger\Monolog\Monolog as MonologBridge;

/**
 *	Monolog Logger Bridge
 *
 * @package         Webiny\Component\Logger\Drivers
 */
class Monolog
{
	public static function getInstance($channelName) {
		return MonologBridge::getInstance($channelName);
	}
}