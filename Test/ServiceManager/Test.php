<?php
use Webiny\Component\ServiceManager\ServiceManager;
use Webiny\Component\ServiceManager\ServiceManagerTrait;

require_once '../../library/autoloader.php';

class Test
{
	use ServiceManagerTrait;

	function index() {
		$service = $this->getService('logger.webiny_logger');
		$service->info('System booted!');
		$service->debug('New log sent');
		$service->emergency('Shit just hit the fan!');
	}
	
}

$test = new Test();
$test->index();


