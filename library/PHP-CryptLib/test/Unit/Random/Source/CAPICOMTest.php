<?php

use CryptLib\Random\Source\CAPICOM;
use CryptLib\Core\Strength;



class Unit_Random_Source_CAPICOMTest extends PHPUnit_Framework_TestCase {

    public static function provideGenerate() {
        $data = array();
        for ($i = 0; $i < 100; $i += 25) {
            $not = $i > 0 ? str_repeat(chr(0), $i) : chr(0);
            $data[] = array($i, $not);
        }
        return $data;
    }

    /**
     * @covers CryptLib\Random\Source\CAPICOM::getStrength
     */
    public function testGetStrength() {
        $strength = new Strength(Strength::MEDIUM);
        $actual = CAPICOM::getStrength();
        $this->assertEquals($actual, $strength);
    }

    /**
     * @covers CryptLib\Random\Source\CAPICOM::generate
     * @dataProvider provideGenerate
     * @group slow
     */
    public function testGenerate($length, $not) {
        $rand = new CAPICOM;
        $stub = $rand->generate($length);
        $this->assertEquals($length, strlen($stub));
    }

}
