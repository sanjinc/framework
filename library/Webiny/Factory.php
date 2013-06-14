<?php


namespace Webiny;


use Webiny\Component\ServiceManager\ServiceManagerTrait;

class Factory {
	use ServiceManagerTrait;


	function __construct($param) {
		$this->_param = $param;
	}

	public function getInstance($name){
		echo $this->_param.' / '.$name;
		return $this->service('logger.WebinySystem');
	}
}