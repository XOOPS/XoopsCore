<?php

namespace Xmf\Test;

use Xmf\Yaml;

class YamlTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    public function testDumpAndLoad()
    {
        $inputArray = ['one' => 1, 'two' => [1, 2], 'three' => ''];

        $string = Yaml::dump($inputArray);
        $this->assertTrue(!empty($string));
        $this->assertInternalType('string', $string);

        $outputArray = Yaml::load((string) $string);
        $this->assertInternalType('array', $outputArray);
        $this->assertSame($inputArray, $outputArray);
    }

    public function testSaveAndRead()
    {
        $tmpfname = tempnam(sys_get_temp_dir(), 'TEST');
        $inputArray = ['one' => 1, 'two' => [1, 2], 'three' => ''];

        $byteCount = Yaml::save($inputArray, $tmpfname);
        $this->assertFalse(false === $byteCount);
        $this->assertGreaterThan(0, $byteCount);

        $outputArray = Yaml::read($tmpfname);
        $this->assertInternalType('array', $outputArray);
        $this->assertSame($inputArray, $outputArray);

        unlink($tmpfname);
    }

    public function testDumpAndLoadWrapped()
    {
        $inputArray = ['one' => 1, 'two' => [1, 2], 'three' => ''];

        $string = Yaml::dumpWrapped($inputArray);
        $this->assertTrue(!empty($string));
        $this->assertInternalType('string', $string);

        $outputArray = Yaml::loadWrapped((string) $string);
        $this->assertInternalType('array', $outputArray);
        $this->assertSame($inputArray, $outputArray);
    }

    public function testDumpAndLoadWrappedStress()
    {
        $inputArray = ['start' => '---', 'end' => '...', 'misc' => 'stuff'];

        $string = Yaml::dumpWrapped($inputArray);
        $this->assertTrue(!empty($string));
        $this->assertInternalType('string', $string);

        $outputArray = Yaml::loadWrapped((string) $string);
        $this->assertInternalType('array', $outputArray);
        $this->assertSame($inputArray, $outputArray);
    }

    public function testDumpAndLoadWrappedStress2()
    {
        $inputArray = ['start' => '---', 'end' => '...', 'misc' => 'stuff'];

        $string = Yaml::dump($inputArray);
        $this->assertTrue(!empty($string));
        $this->assertInternalType('string', $string);

        $outputArray = Yaml::loadWrapped((string) $string);
        $this->assertInternalType('array', $outputArray);
        $this->assertSame($inputArray, $outputArray);
    }

    public function testSaveAndReadWrapped()
    {
        $tmpfname = tempnam(sys_get_temp_dir(), 'TEST');
        $inputArray = ['one' => 1, 'two' => [1, 2], 'three' => ''];

        $byteCount = Yaml::saveWrapped($inputArray, $tmpfname);
        $this->assertFalse(false === $byteCount);
        $this->assertGreaterThan(0, $byteCount);

        $outputArray = Yaml::readWrapped($tmpfname);
        $this->assertInternalType('array', $outputArray);
        $this->assertSame($inputArray, $outputArray);

        unlink($tmpfname);
    }
}
