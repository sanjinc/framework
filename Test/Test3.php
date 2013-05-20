<?php

define('WF', '/var/www/newwebiny/framework');
require_once '../WebinyFramework.php';

class Test
{
	function test() {
		$processor = function($record){
			$record['extra']['country'] = 'Ukraine';
			return $record;
		};


		$logger = new Monolog\Logger('Debug');
		$handler = new Monolog\Handler\StreamHandler(WF.'/Test/logger.log', true);

		$logger->pushHandler($handler);
		$logger->pushProcessor($processor);

		$logger->addInfo('Testing Info call', ['name' => 'Pavel']);
	}

}

$logger = \Webiny\Component\Logger\Logger::getInstance('Module builder');
$logger->addHandler(new \Webiny\Component\Logger\Handlers\StreamHandler(WF.'/Test/logger.log'));

$logger->info('Got to this point...');

die(print_r($logger));

$test = new Test();
$test->test();