<?php

define('WF', '/www/webiny/framework');
require_once '../WebinyFramework.php';

class Test
{
	function test() {
		$processor = function ($record) {
			$record['extra']['country'] = 'Ukraine';

			return $record;
		};


		$logger = new Monolog\Logger('Debug');
		$handler = new Monolog\Handler\StreamHandler(WF . '/Test/logger.log', true);

		$logger->pushHandler($handler);
		$logger->pushProcessor($processor);

		$logger->addInfo('Testing Info call', ['name' => 'Pavel']);
	}

	function config() {
		$config = \Webiny\Component\Config\Config::PHP(WF . '/Test/Configs/config.php');
		$config2 = \Webiny\Component\Config\Config::Json(WF . '/Test/Configs/config.json');
		$config3 = \Webiny\Component\Config\Config::Yaml(WF . '/Test/Configs/config.yaml');
		$config4 = \Webiny\Component\Config\Config::Ini(WF . '/Test/Configs/config.ini');

		$config->mergeWith([$config2, ['group2' => ['custom' => 'data']], $config3])->mergeWith($config4);
		die(print_r($config->toArray()));
	}

}

$test = new Test();
$test->config();