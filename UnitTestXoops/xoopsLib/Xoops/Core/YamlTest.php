<?php
namespace Xoops\Core;

require_once(__DIR__.'/../../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class YamlTest extends \MY_UnitTestCase
{
    protected $myclass = 'Xoops\Core\Yaml';

    public function test___construct()
	{
		$instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
    }

    /**
     * @covers Xoops\Core\Yaml::dump
     * @covers Xoops\Core\Yaml::load
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
     * @covers Xoops\Core\Yaml::save
     * @covers Xoops\Core\Yaml::read
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
}
