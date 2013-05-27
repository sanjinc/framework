<?php

use Webiny\Bridge\Logger\Webiny\Record;
use Webiny\Component\Config\Config;
use Webiny\Component\Logger\Drivers\Webiny\Formatters\WebinyTrayFormatter;
use Webiny\Component\Logger\Drivers\Webiny\Handlers\UDPHandler;
use Webiny\Component\Logger\Logger;

define('WF', '/www/webiny/framework');
require_once '../../library/autoloader.php';

class Test
{
	function testLogger() {
		// Setup Bootstrap logger
		$logger = Logger::Webiny('Bootstrap');
		$handler = new UDPHandler([], true, true, "192.168.1.10:41234");
		$formatter = new WebinyTrayFormatter();
		$handler->setFormatter($formatter)->addProcessor(function(Record $record){
			if($record->level == 'info'){
				$record->extra['data'] = strtoupper($record->level);
			}
		});
		$logger->addHandler($handler);

		// Log some messages
		$logger->info('Bootstrap started');
		$logger->debug('Config data', ['config' => 'data']);
		$logger->alert('Webiny config file is missing!');
		$logger->info('Bootstrap finished!');
	}
}

$test = new Test();
$test->testLogger();