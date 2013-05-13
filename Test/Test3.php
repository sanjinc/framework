<?php
define('WF', '/www/webiny/framework');
require_once '../WebinyFramework.php';

/**
 * Custom Resource
 * $config4 = \Webiny\Component\Config\Config::parseResource(['name' => 'Test']);
 * $config4 = \Webiny\Component\Config\Config::parseResource(new CustomDriver(realpath(__DIR__).'/Configs/config.ext'));
 */



$config3 = \Webiny\Component\Config\Config::Php(realpath(__DIR__) . '/Configs/config.php');
die($config3->getAsYaml());