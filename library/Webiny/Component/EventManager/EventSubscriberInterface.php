<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\EventManager;

/**
 * This interface is used for event subscriber classes
 *
 * @package   Webiny\Component\EventManager
 */
interface EventSubscriberInterface
{
	/**
	 * Subscribe to events
	 * @return void
	 */
	public function subscribe();
}