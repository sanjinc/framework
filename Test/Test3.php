<?php
use Webiny\Component\Config\ConfigTrait;

define('WF', '/www/webiny/framework');
require_once '../WebinyFramework.php';

/**
 * Custom Resource
 * $config4 = \Webiny\Component\Config\Config::parseResource(['name' => 'Test']);
 * $config4 = \Webiny\Component\Config\Config::parseResource(new CustomDriver(realpath(__DIR__).'/Configs/config.ext'));
 */

class Test
{
	use ConfigTrait;

	function test() {
		$config = $this->getJsonConfig(['name' => 'Test', 'test' => ['key' => 'value']]);
		die($config->getAsIni());

	}

}

$test = new Test();
$test->test();