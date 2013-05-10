<?php
use Webiny\StdLib\Exception\ExceptionAbstract;

require_once '../WebinyFramework.php';

class CustomException extends ExceptionAbstract{
	const MSG_NUM_INVALID = 114;

	protected static $_messages = [114 => 'Your number is invalid: %s and it\'s smaller than %s'];
}

function doSomething($a){
	if($a<4){
		throw new CustomException(CustomException::MSG_BAD_FUNC_CALL, [$a, 4]);
	}
}

try{
	doSomething(3);
}catch (CustomException $e){
	// don+t catch it
	throw $e;
}