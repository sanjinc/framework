<?php

use Webiny\Component\Config\Config;
use Webiny\Component\Logger\Drivers\Webiny\Formatters\FileFormatter;
use Webiny\Component\Logger\Drivers\Webiny\Handlers\FileHandler;
use Webiny\Component\Logger\Drivers\Webiny\Processors\TestProcessor;
use Webiny\Component\Logger\Logger;

define('WF', '/var/www/newwebiny/framework');
require_once '../library/autoloader.php';

class Test
{
	function testLogger() {
		// Setup Bootstrap logger
		$logger = Logger::Webiny('Bootstrap');
		$handler = new FileHandler(WF.'/Test/Logger/logger.log');
		$formatter = new FileFormatter();
		$processor = new TestProcessor();
		$handler->setFormatter($formatter)->addProcessor($processor);
		$logger->addHandler($handler);

		// Log some messages
		$logger->info('Bootstrap finished!', [$formatter]);
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