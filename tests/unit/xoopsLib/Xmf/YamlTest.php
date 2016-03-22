<?php
namespace Xmf;

require_once(dirname(__FILE__).'/../../init_new.php');

/**
 * PHPUnit special settings :
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class YamlTest extends \PHPUnit_Framework_TestCase
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

    /**
     * @covers Xmf\Yaml::dump
     * @covers Xmf\Yaml::load
     */
    public function testDumpAndLoad()
    {
        $inputArray = array('one' => 1, 'two' => array(1,2), 'three' => '');

        $string = Yaml::dump($inputArray);
        $this->assertTrue(!empty($string));
        $this->assertTrue(is_string($string));

        $outputArray = Yaml::load((string) $string);
        $this->assertTrue(is_array($outputArray));
        $this->assertSame($inputArray, $outputArray);
    }

    /**
     * @covers Xmf\Yaml::save
     * @covers Xmf\Yaml::read
     */
    public function testSaveAndRead()
    {
        $tmpfname = tempnam(sys_get_temp_dir(), 'TEST');
        $inputArray = array('one' => 1, 'two' => array(1,2), 'three' => '');

        $byteCount = Yaml::save($inputArray, $tmpfname);
        $this->assertFalse($byteCount === false);
        $this->assertGreaterThan(0, $byteCount);

        $outputArray = Yaml::read($tmpfname);
        $this->assertTrue(is_array($outputArray));
        $this->assertSame($inputArray, $outputArray);

        unlink($tmpfname);
    }

    /**
     * @covers Xmf\Yaml::dumpWrapped
     * @covers Xmf\Yaml::loadWrapped
     */
    public function testDumpAndLoadWrapped()
    {
        $inputArray = array('one' => 1, 'two' => array(1,2), 'three' => '');

        $string = Yaml::dumpWrapped($inputArray);
        $this->assertTrue(!empty($string));
        $this->assertTrue(is_string($string));

        $outputArray = Yaml::loadWrapped((string) $string);
        $this->assertTrue(is_array($outputArray));
        $this->assertSame($inputArray, $outputArray);
    }

    /**
     * @covers Xmf\Yaml::saveWrapped
     * @covers Xmf\Yaml::readWrapped
     */
    public function testSaveAndReadWrapped()
    {
        $tmpfname = tempnam(sys_get_temp_dir(), 'TEST');
        $inputArray = array('one' => 1, 'two' => array(1,2), 'three' => '');

        $byteCount = Yaml::saveWrapped($inputArray, $tmpfname);
        $this->assertFalse($byteCount === false);
        $this->assertGreaterThan(0, $byteCount);

        $outputArray = Yaml::readWrapped($tmpfname);
        $this->assertTrue(is_array($outputArray));
        $this->assertSame($inputArray, $outputArray);

        unlink($tmpfname);
    }
}
