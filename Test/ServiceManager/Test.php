<?php
use Webiny\Component\ServiceManager\ServiceManager;
use Webiny\Component\ServiceManager\ServiceManagerTrait;

require_once '../../library/autoloader.php';

class Test
{
	use ServiceManagerTrait;

	function index() {
		$this->getService('logger.WebinySystem')->info('Bootstrap successful!!');
		$this->getService('logger.WebinyEcommerce')->alert('Sold something!!');
	}

}

$test = new Test();
$test->index();


