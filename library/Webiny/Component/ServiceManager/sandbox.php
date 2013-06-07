<?php

/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

/**
EventManager:
	class: "%logger.class%"
	arguments: ["EventManager", "%logger.driver.class%"]
	calls:
	  - [addHandler, ["@logger.handlers.UDPTray"]]
	scope: container #Singleton - default
*/
