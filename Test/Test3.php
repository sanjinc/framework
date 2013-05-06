<?php
namespace Webiny\Test;

use Webiny\Component\Config\Config;
use Webiny\Component\Config\ConfigException;

require_once '../WebinyFramework.php';

class Test3
{

    public function test()
    {
        try {
            /* @var $configObject Config */
            $configObject = Config::Ini('config.ini');
        } catch (ConfigException $e) {
            die("Exception caught!");
        }

        echo $configObject->address->country . " (".$configObject->name."/". $configObject->address->number.") - ";
        $configObject->address->country = 'Ukraine';
        echo $configObject->address->country . " - ";
        $configObject->address->country = [
            'continent' => 'Europe',
            'part'      => 'East'
        ];
        echo $configObject->address->country->part . "\n\n\n";

        die(print_r($configObject));

    }
}

$test = new Test3();
$test->test();