<?php

use Webiny\Component\Config\Config;
use Webiny\Component\Logger\Formatters\FileFormatter;
use Webiny\Component\Logger\Handlers\FileHandler;
use Webiny\Component\Logger\Logger;

define('WF', '/www/webiny/framework');
require_once '../WebinyFramework.php';

class Test
{
	function testLogger() {
		$logger = Logger::Webiny('Bootstrap');
		$handler = new FileHandler(WF.'/Test/Logger/logger.log');
		$formatter = new FileFormatter(null, 'd.m.Y H-i-s');
		$handler->setFormatter($formatter);

		$logger->addHandler($handler);
		$logger->debug('Started bootstrap process...');
		$logger->alert('Wrong datetime format provided...');
		$logger->info('Bootstrap finished!');
	}

	function config() {
		$config = Config::PHP(WF . '/Test/Configs/config.php');
		$config2 = Config::Json(WF . '/Test/Configs/config.json');
		$config3 = Config::Yaml(WF . '/Test/Configs/config.yaml');
		$config4 = Config::Ini(WF . '/Test/Configs/config.ini');

		$config->mergeWith([$config2, ['group2' => ['custom' => 'data']], $config3])->mergeWith($config4);
		die(print_r($config->toArray()));
	}

}

$test = new Test();
$test->testLogger();