<?php

use Webiny\Component\Storage\Driver\Local;
use Webiny\Component\Storage\File;
use Webiny\Component\Storage\Storage;

define('WF', '/var/www/newwebiny/framework');
define('ROOT', '/var/www/newwebiny/framework/Test/Storage/uploads');
require_once '../../library/autoloader.php';

class Test
{

	function storage(){
		$storage = new Storage(new Local(ROOT, true));

		$file = new File('sub/folder/adrian.gif', $storage);


		/*$content = file_get_contents('http://www.w3schools.com/images/w3schoolslogoNEW310113.gif');
		$file->setContent($content)->save();*/

		$file->getTimeModified();
		$file->touch();
		echo "<br />".$file->getTimeModified();
		echo "<br />Size: ".$file->getSize();

	}

	function dropbox(){
		$storage = new Storage(new Dropbox());
	}
}

$test = new Test();
$test->dropbox();