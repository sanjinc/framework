<?php
require_once '../library/autoloader.php';


class Test{

	use \Webiny\WebinyTrait;

	function __construct(){
		$a  =$this->webiny();
		echo $a::WF_CACHE_ID;
	}

}

$t = new Test();