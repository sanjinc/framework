<?php
require_once '../library/autoloader.php';


class Test{

	use \Webiny\WebinyTrait, \Webiny\Component\Config\ConfigTrait, \Webiny\Component\StdLib\StdLibTrait;

	function __construct(){
		$datetime1 = $this->datetime("2013-09-28 22:44:17+02");
		$datetime2 = $this->datetime("now");
		$interval = $datetime1->diff($datetime2);
		$minutes = $interval['i'];
		if($minutes > 3) {
			die("TIMEOUT");
		} else {
			die("COOL");
		}
	}
}
$t = new Test();