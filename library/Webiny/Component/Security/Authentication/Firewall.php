<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Security\Authentication;

use Webiny\StdLib\SingletonTrait;

/**
 * Description
 *
 * @package		 Webiny\Component\Security\Authenticatio
 */
 
class Firewall{

	function __construct($firewallConfig){
		// validate config

		// parse general data (name, urls, etc)

		// setup authorization layer

		// identify user

		// check on firewall if user has access

			// if true check on authorization layer if user has access

		// if user doesn't have access trigger the authentication process
	}

	private function _getUserProvider(){
		// returns an instance of user provider for current firewall
	}

	private function _getEncoder(){
		// return an instance of encoder for current firewall
	}


}