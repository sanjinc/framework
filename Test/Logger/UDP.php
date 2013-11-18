<?php

use Webiny\Component\Config\Config;
use Webiny\Component\Config\ConfigTrait;
use Webiny\Component\Logger\Formatters\WebinyTrayFormatter;
use Webiny\Component\Logger\Handlers\UDPHandler;
use Webiny\Component\Logger\Processors\FileLineProcessor;
use Webiny\Component\Logger\Processors\MemoryUsageProcessor;
use Webiny\Component\Logger\Logger;
use Webiny\Component\StdLib\StdLibTrait;
use Webiny\WebinyTrait;

define('WF', '/www/webiny/framework');
require_once '../../library/autoloader.php';

class Test
{
	use WebinyTrait, StdLibTrait;

	function testLogger() {
		// Setup Bootstrap logger
		$logger = Logger::Webiny('EC.Shipping.Method');
		$handler = new UDPHandler([], true, true);
		$formatter = new WebinyTrayFormatter();
		$handler->setFormatter($formatter);
		$handler->addProcessor(new FileLineProcessor());
		$handler->addProcessor(new MemoryUsageProcessor());
		$logger->addHandler($handler);


		$logger2 = Logger::Webiny('WF.Request');
		$logger2->addHandler($handler);

		// Log some messages
		$logger->info('Created new method!');
		$logger2->info('Digo se sistem!');
		$ar = [1,2,3];
		$logger->emergency('Method failed to load!', [
		 	'value' => $this->str("BUUU"),
		   	'arr' => $this->arr($ar),
			'arr2' => [
				'key' => 'value',
				'key2' => 12
			],
			'arr3' => ['a', 'b', 'c'],
			'arr3' => ['a', 'b', 'c']
		]);
		$logger2->alert('Nes sam usro!');
		$logger->debug('Method data', ['method' => ['id' => 12, 'name' => 'DHL']]);

	}
}

$test = new Test();
$test->testLogger();