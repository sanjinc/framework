<?php

/**
 *
 * ._ => "_"
 * ._12 => isto ko i gore
 * .asd_ => zadnji underscore
 * .12 => is_int
 * 1. Prvi znak mora biti unutar [a-z]
 * 2.
 * (int)
 * ._12
 * .-
 * ._
 * .12*
 * .abc_
 * .asd12_
 */


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
            $configObject = Config::Ini(realpath(__DIR__).'/config.ini');
        } catch (ConfigException $e) {
            die($e->getMessage());
            die("Exception caught!");
        }

        die(print_r($configObject));

    }
}

$test = new Test3();
$test->test();