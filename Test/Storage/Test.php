<?php
use Webiny\Component\EventManager\EventManagerTrait;
use Webiny\Component\Storage\Driver\Dropbox;
use Webiny\Component\Storage\Driver\Local\Local;
use Webiny\Component\Storage\File\LocalFile;
use Webiny\Component\Storage\Directory\Directory;
use Webiny\Component\Storage\Storage;
use Webiny\Component\Storage\StorageTrait;

define('WF', '/www/webiny/framework');
define('ABS_ROOT', '/www/webiny/framework/Test/Storage/uploads');
define('URL_ROOT', 'http://wf.com/Test/Storage/uploads');
require_once '../../library/autoloader.php';

class Test
{
	use EventManagerTrait, StorageTrait;

	function file() {
		$storage = $this->storage('local');
		$file = new LocalFile('superfile.gif', $storage);
		$contents = file_get_contents('http://www.w3schools.com/images/w3schoolslogoNEW310113.gif');
		$file->setContents($contents);

		echo $file->getKey();
		die();

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
$test->file();