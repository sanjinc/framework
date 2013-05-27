<?php

use CryptLibTest\Mocks\Random\Mixer;
use CryptLibTest\Mocks\Random\Source;

use CryptLib\Random\Generator;

class Vectors_Random_GeneratorTest extends PHPUnit_Framework_TestCase {

    public static function provideGenerateInt() {
        return array(
            // First, lets test each offset based range
            array(0, 7),
            array(0, 15),
            array(0, 31),
            array(0, 63),
            array(0, 127),
            array(0, 255),
            array(0, 511),
            array(0, 1023),
            // Let's try a range not starting at 0
            array(8, 15),
            // Let's try a range with a negative number
            array(-18, -11),
            // Let's try a non-power-of-2 range
            array(10, 100),
            // Finally, let's try two large numbers
            array(100000, 100007),
            array(100000000, 100002047),
            // Now, let's force a few loops by setting a valid offset
            array(0, 5, 2),
            array(0, 9, 5),
            array(0, 27, 4),
        );
    }

    public function provideGenerateString() {
        return array(
            array(
                1,
                1,
                "01",
                function($j) {
                    return str_pad(decbin($j), 1, '0', STR_PAD_LEFT);
                }
            ),
            array(
                2,
                3,
                "01",
                function($j) {
                    return str_pad(decbin($j), 2, '0', STR_PAD_LEFT);
                }
            ),
            array(
                3,
                7,
                "01",
                function($j) {
                    return str_pad(decbin($j), 3, '0', STR_PAD_LEFT);
                }
            ),
            array(
                8,
                255,
                "01",
                function($j) {
                    return str_pad(decbin($j), 8, '0', STR_PAD_LEFT);
                }
            ),
            array(
                16,
                255,
                "01",
                function($j) {
                    return str_pad(decbin($j), 16, '0', STR_PAD_LEFT);
                }
            ),
            array(
                1,
                15,
                "0123456789abcdef",
                function($j) {
                    return str_pad(dechex($j), 1, '0', STR_PAD_LEFT);
                }
            ),
            array(
                2,
                255,
                "0123456789abcdef",
                function($j) {
                    return str_pad(dechex($j), 2, '0', STR_PAD_LEFT);
                }
            ),
            array(
                3,
                300,
                "0123456789abcdef",
                function($j) {
                    return str_pad(dechex($j), 3, '0', STR_PAD_LEFT);
                }
            ),
        );
    }

    public static function provideGenerators() {
        $factory = new \CryptLib\Random\Factory;
        $generator = $factory->getLowStrengthGenerator();
        $sources = $generator->getSources();
        $ret = array();

        $ret[] = array(new Generator($sources, new \CryptLib\Random\Mixer\Hash), 10000, 'hash');
        $ret[] = array(new Generator($sources, new \CryptLib\Random\Mixer\DES), 10000, 'des');
        $ret[] = array(new Generator($sources, new \CryptLib\Random\Mixer\Rijndael), 10000, 'rijndael');
        return $ret;
    }

    /**
     * This test asserts that the algorithm that generates the integers does not
     * actually introduce any bias into the generated numbers.  If this test
     * passes, the generated integers from the generator will be as unbiased as
     * the sources that provide the data.
     *
     * @dataProvider provideGenerateInt
     */
    public function testGenerateInt($min, $max, $offset = 0) {
        $generator = $this->getGenerator($max - $min + $offset);
        for ($i = $max; $i >= $min; $i--) {
            $this->assertEquals($i, $generator->generateInt($min, $max));
        }
    }

    /**
     * This test asserts that the algorithm that generates the strings does not
     * actually introduce any bias into the generated numbers.  If this test
     * passes, the generated strings from the generator will be as unbiased as
     * the sources that provide the data.
     *
     * @dataProvider provideGenerateString
     */
    public function testGenerateString($length, $max, $chrs, $func) {
        $generator = $this->getGenerator($max);
        for ($i = $max; $i >= 0; $i--) {
            $this->assertEquals($func($i), $generator->generateString($length, $chrs));
        }
    }

    /**
     * This generator generates two bytes at a time, and uses each 8 bit segment of
     * the generated byte as a coordinate on a grid (so 01011010 would be the
     * coordinate (0101, 1010) or (5, 10).  These are used as inputs to a MonteCarlo
     * algorithm for the integral of y=x over a 15x15 grid.  The expected answer is
     * 1/2 * 15 * 15 (or 1/2 * base * height, since the result is a triangle).
     * Therefore, if we get an answer close to that, we know the generator is good.
     *
     * Now, since the area under the line should be equal to the area above the line.
     * Therefore, the ratio of the two areas should be equal.  This way, we can avoid
     * computing total to figure out the areas.
     *
     * I have set the bounds on the test to be 80% and 120%.  Meaning that I will
     * consider the test valid and unbiased if the number of random elements that
     * fall under (inside) of the line and the number that fall outside of the line
     * are at most 20% apart.
     *
     * Since testing randomness is not reliable or repeatable, I will only fail the
     * test in two different scenarios.  The first is if after the iterations the
     * outside or the inside is 0.  The chances of that happening are so low that
     * if it happens, it's relatively safe to assume that something bad happened. The
     * second scenario happens when the ratio is outside of the 20% tolerance.  If
     * that happens, I will re-run the entire test.  If that test is outside of the 20%
     * tolerance, then the test will fail
     *
     *
     * @dataProvider provideGenerators
     */
    public function testGenerate(\CryptLib\Random\Generator $generator, $times) {
        $ratio = $this->doTestGenerate($generator, $times);
        if ($ratio < 0.8 || $ratio > 1.2) {
            $ratio2 = $this->doTestGenerate($generator, $times);
            if ($ratio2 > 1.2 || $ratio2 < 0.8) {
                $this->fail(
                    sprintf(
                        'The test failed multiple runs with final ratios %f and %f',
                        $ratio,
                        $ratio2
                    )
                );
            }
        }
    }

    protected function doTestGenerate(\CryptLib\Random\Generator $generator, $times) {
        $inside = 0;
        $outside = 0;
        $on = 0;
        for ($i = 0; $i < $times; $i++) {
            $byte = $generator->generate(2);
            $byte = unpack('n', $byte);
            $byte = array_shift($byte);
            $xCoord = ($byte >> 8);
            $yCoord = ($byte & 0xFF);
            if ($xCoord < $yCoord) {
                $outside++;
            } elseif ($xCoord == $yCoord) {
                $on++;
            } else {
                $inside++;
            }
        }
        $this->assertGreaterThan(0, $outside, 'Outside Is 0');
        $this->assertGreaterThan(0, $inside, 'Inside Is 0');
        $ratio = $inside / $outside;
        return $ratio;
    }

    public function getGenerator($random) {
        $source1  = new Source(array(
            'generate' => function ($size) use (&$random) {
                $ret = pack('N', $random);
                $random--;
                return substr($ret, -1 * $size);
            }
        ));
        $sources = array($source1);
        $mixer   = new Mixer(array(
            'mix'=> function(array $sources) {
                if (empty($sources)) return '';
                return array_pop($sources);
            }
        ));
        return new Generator($sources, $mixer);
    }

}
