<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Security;

use Webiny\StdLib\SingletonTrait;

/**
 * Description
 *
 * @package		 Webiny\Component\Security
 */

class Security{
	use SingletonTrait;


	public function init($securityConfig){
		// validate the config

		// setup authentication layer - firewalls

			// for each firewall setup authorization layer - access control

			// on each firewall validate access to current resource (URL)
	}

	public function getUser(){

	}
}
