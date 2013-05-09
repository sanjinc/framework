<?php

require_once '../WebinyFramework.php';
/*
 # Built-in drivers
$config = \Webiny\Component\Config\Config::Ini(realpath(__DIR__).'/Configs/config.ini');
$config2 = \Webiny\Component\Config\Config::Json(realpath(__DIR__).'/Configs/config.json');
$config3 = \Webiny\Component\Config\Config::Php(realpath(__DIR__).'/Configs/config.php');

# Custom Resource #
$config4 = \Webiny\Component\Config\Config::parseResource(['name' => 'Test']);
$config4 = \Webiny\Component\Config\Config::parseResource(new CustomDriver(realpath(__DIR__).'/Configs/config.ext'));
*/

$config3 = \Webiny\Component\Config\Config::Ini(realpath(__DIR__).'/Configs/config.ini');
die(print_r($config3));