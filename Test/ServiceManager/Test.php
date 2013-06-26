<?php
use Webiny\Component\ServiceManager\ServiceManager;
use Webiny\Component\ServiceManager\ServiceManagerTrait;

require_once '../../library/autoloader.php';

class Test
{
	use ServiceManagerTrait;

	function index() {
		$service = $this->service('logger.webiny_logger');
		$service->info('System booted!');
		$service->debug('Got a big file...');
		$service->debug('Dumped a big file!');
	}
	
}

$test = new Test();
$test->index();


