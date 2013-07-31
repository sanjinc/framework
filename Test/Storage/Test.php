<?php

use Webiny\Component\EventManager\EventManagerTrait;
use Webiny\Component\Storage\Driver\Dropbox;
use Webiny\Component\Storage\Driver\Local\Local;
use Webiny\Component\Storage\File\LocalFile;
use Webiny\Component\Storage\Directory\Directory;
use Webiny\Component\Storage\Storage;

define('WF', '/www/webiny/framework');
define('ABS_ROOT', '/www/webiny/framework/Test/Storage/uploads');
define('URL_ROOT', 'http://wf.com/Test/Storage/uploads');
require_once '../../library/autoloader.php';
use \Dropbox as dbx;
use Webiny\Component\Storage\StorageEvent;

class Test
{
	use EventManagerTrait;

	function file() {
		$driver = new Local(ABS_ROOT, URL_ROOT, true, true);
		$storage = new Storage($driver);

		$file = new LocalFile('superfile', $storage);
		$content = file_get_contents('http://www.w3schools.com/images/w3schoolslogoNEW310113.gif');
		$file->setContent($content);

		echo $file->getAbsolutePath();
		echo "<br /> URL: ";
		echo $file->getUrl();
		echo "<br /> Size: ";
		echo $file->getSize();
		echo "<br /> Time modified: ";
		echo $file->getTimeModified(true);
		echo "<br />Key: ";
		echo $file->getKey();

	}

	function directory() {
		$driver = new Local(ABS_ROOT, URL_ROOT, true, true);
		$storage = new Storage($driver);

		$dir = new Directory('2013', $storage, false);

		foreach($dir as $item){
			if($item instanceof Directory){
				echo $item->getKey()."<br />";
				foreach($item as $i){
					echo "----".$i->getKey()."<br />";
				}
			} else {
				echo $item->getKey()."<br />";
			}

		}

	}
}

$test = new Test();
$test->directory();