<?php
/**
 * Webiny Framework (http://www.webiny.com/framework)
 *
 * @link      http://www.webiny.com/wf-snv for the canonical source repository
 * @copyright Copyright (c) 2009-2013 Webiny LTD. (http://www.webiny.com)
 * @license   http://www.webiny.com/framework/license
 */

namespace Webiny\Component\Security;

use Webiny\Component\Config\ConfigObject;
use Webiny\Component\Security\Authentication\Firewall;
use Webiny\StdLib\SingletonTrait;
use Webiny\WebinyTrait;

/**
 * Description
 *
 * @package		 Webiny\Component\Security
 */

class Security{
	use SingletonTrait, WebinyTrait;

	private $_config;
	private $_firewalls;

	public function init(){
		// validate the config
		if(!isset($this->webiny()->getConfig()->security) || !is_object($this->webiny()->getConfig()->security->firewall)){
			return false;
		}
		$this->_config = $this->webiny()->getConfig()->security;

		// setup authentication layer - firewalls
		foreach($this->webiny()->getConfig()->security->firewall as $firewallKey => $firewallConfig){
			$this->_initFirewall($firewallKey, $firewallConfig);
		}
	}

	private function _initFirewall($firewallKey, ConfigObject $config){
		$this->_firewalls[$firewallKey] = new Firewall($firewallKey, $config);
	}
}
