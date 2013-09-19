<?php
require_once '../library/autoloader.php';


class Test{

	use \Webiny\WebinyTrait, \Webiny\Component\Config\ConfigTrait;

	function __construct(){
		$path = realpath(__DIR__).'/Configs/config.yaml';
		$config = $this->config()->yaml($path);
		$config2 = unserialize(serialize($config));



		
		/**
		 * @var $config2 \Webiny\Component\Config\ConfigObject
		 */
		die(print_r($config2));
		die();
	}
}
$t = new Test();