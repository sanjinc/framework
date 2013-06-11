<?php
namespace Webiny\Component\ServiceManager;

class ServiceScope
{
	const CONTAINER = 'container';
	const PROTOTYPE = 'prototype';

	public static function exists($scope){
		if($scope == self::CONTAINER){
			return true;
		}

		if($scope == self::PROTOTYPE){
			return true;
		}

		return false;
	}

}