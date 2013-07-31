<?php

use Webiny\Component\EventManager\EventManagerTrait;
use Webiny\Component\Storage\Driver\Dropbox;
use Webiny\Component\Storage\Driver\Local;
use Webiny\Component\Storage\File\LocalFile;
use Webiny\Component\Storage\Storage;

define('WF', '/var/www/newwebiny/framework');
define('ABS_ROOT', '/var/www/newwebiny/framework/Test/Storage/uploads');
define('URL_ROOT', 'http://wf.com/Test/Storage/uploads');
require_once '../../library/autoloader.php';
use \Dropbox as dbx;
use Webiny\Component\Storage\StorageEvent;

class Test
{
	use EventManagerTrait;

	function storage() {

/*		$this->eventManager()->listen(StorageEvent::FILE_SAVED)->handler(function(StorageEvent $e){
			$e->getFile()->getTimeModified();
			echo $e->getFile()->getAbsolutePath();
			die(print_r($e->getFile()));
		});*/

		$driver = new Local(ABS_ROOT, URL_ROOT, true, true);
		$storage = new Storage($driver);

		$file = new LocalFile('2013/07/31/test.gif', $storage);
		$file->delete();

		/*$content = file_get_contents('http://www.w3schools.com/images/w3schoolslogoNEW310113.gif');
		$file->setContent($content);*/

		/*echo $file->getAbsolutePath();
		echo "<br /> URL: ";
		echo $file->getUrl();
		echo "<br /> Size: ";
		echo $file->getSize();
		echo "<br /> Time modified: ";
		echo $file->getTimeModified(true);
		echo "<br />Key: ";
		echo $file->getKey();*/

	}
}

$test = new Test();
$test->storage();