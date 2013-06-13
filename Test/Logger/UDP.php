<?php

use Webiny\Bridge\Logger\Webiny\Record;
use Webiny\Component\Config\Config;
use Webiny\Component\Config\ConfigTrait;
use Webiny\Component\Logger\Drivers\Webiny\Formatters\WebinyTrayFormatter;
use Webiny\Component\Logger\Drivers\Webiny\Handlers\UDPHandler;
use Webiny\Component\Logger\Logger;

define('WF', '/www/webiny/framework');
require_once '../../library/autoloader.php';

class Test
{
	use \Webiny\WebinyTrait;

	function testLogger() {
		// Setup Bootstrap logger
		$logger = Logger::Webiny('EC.Shipping.Method');
		$handler = new UDPHandler([], true, true);
		$formatter = new WebinyTrayFormatter();
		$handler->setFormatter($formatter);
		$logger->addHandler($handler);


		$logger2 = Logger::Webiny('WF.Request');
		$logger2->addHandler($handler);

		// Log some messages
		$logger->info('Created new method!');
		$logger2->info('Digo se sistem!');
		$logger->alert('Method failed to load!');
		$logger2->alert('Nes sam usro!');
		$logger->debug('Method data', ['method' => ['id' => 12, 'name' => 'DHL']]);

	}
}

$test = new Test();
$test->testLogger();