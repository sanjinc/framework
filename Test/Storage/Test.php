<?php

use Webiny\Component\Storage\Driver\Dropbox;
use Webiny\Component\Storage\Driver\Local;
use Webiny\Component\Storage\File;
use Webiny\Component\Storage\Storage;

define('WF', '/var/www/newwebiny/framework');
define('ROOT', '/var/www/newwebiny/framework/Test/Storage/uploads');
require_once '../../library/autoloader.php';
require_once WF . "/library/Dropbox-SDK/lib/Dropbox/autoload.php";
use \Dropbox as dbx;

class Test
{

	function storage() {
		$storage = new Storage(new Local(ROOT, true));

		$file = new File('sub/folder/adrian.gif', $storage);


		/*$content = file_get_contents('http://www.w3schools.com/images/w3schoolslogoNEW310113.gif');
		$file->setContent($content)->save();*/

		$file->getTimeModified();
		$file->touch();
		echo "<br />" . $file->getTimeModified();
		echo "<br />Size: " . $file->getSize();

	}

	function dropbox() {
		/*$appInfo = new dbx\AppInfo('6loj69ggdogv3ho', '7lptfmvip6633gr');
		$webAuth = new dbx\WebAuthNoRedirect($appInfo, "PHP-Example/1.0");*/

		/**
		 * $authorizeUrl = $webAuth->start();
		 * echo $authorizeUrl;
		 * - visit URL
		 */

		/**
		 * RESULT: -BWR8GxdgLYAAAAAAAAAAVPv_O65lJ71Eu98T5MQ5j8
		 */

		/**
		 * list($accessToken, $dropboxUserId) = $webAuth->finish('-BWR8GxdgLYAAAAAAAAAAVPv_O65lJ71Eu98T5MQ5j8');
		 */

		# 0qSf3dvHi-YAAAAAAAAAAc4lEM2uEipsmn1hep_iJin43bFySrDxoJqLvcX6lCbR

		$client = new dbx\Client('0qSf3dvHi-YAAAAAAAAAAc4lEM2uEipsmn1hep_iJin43bFySrDxoJqLvcX6lCbR', '85427748');
		$storage = new Storage(new Dropbox($client));

		/**
		 * $file = $storage->read('/veleri.zip');
		 * 		ILI
		 * $file2 = new File('veleri.zip', $storage);
		 *
		 * $storage->read() fire-a event sa file objektom da bi se moglo odraditi loadanje meta podataka iz APP-a
		 * File objekt getContent(): $this->_storage->read($this)
		 */


		$data = $storage->read('/BS-TRUNK-TO-STABLE.log');


		var_dump($data);

	}
}

$test = new Test();
$test->dropbox();