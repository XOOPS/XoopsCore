<?php
require_once(dirname(__FILE__).'/../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class MyTextSanitizerExtensionClass extends MyTextSanitizerExtension
{
}

class Module_MyTextSanitizerExtensionTest extends \PHPUnit_Framework_TestCase
{
    protected $myAbstract = 'MyTextSanitizerExtension';
    protected $myClass = 'MyTextSanitizerExtensionClass';
    protected $ts = null;

    public function setUp()
	{
        $this->ts = MyTextSanitizer::getInstance();
    }

    public function test___construct()
	{
        $extension = new $this->myClass($this->ts);
        $this->assertInstanceOf($this->myAbstract, $extension);
        $this->assertEquals($this->ts, $extension->ts);
        $this->assertEquals(\XoopsBaseConfig::get('url') . '/images/form', $extension->image_path);
    }

    public function test_loadConfig()
	{
		$class = $this->myClass;
        $config = $class::loadConfig();
        $this->assertTrue(is_array($config));
    }

    public function test_mergeConfig()
	{
		$class = $this->myClass;
        $array1 = array('x' => 'toto');
        $array2 = array('y' => 'titi');
        $config = $class::mergeConfig($array1,$array2);
        $this->assertTrue(is_array($config));
        $this->assertEquals(count($array1)+count($array2), count($config));
        $this->assertEquals($array1['x'], $config['x']);
        $this->assertEquals($array2['y'], $config['y']);
    }

    public function test_mergeConfig100()
	{
		$class = $this->myClass;
        $array1 = array('x' => 'toto');
        $array2 = array('y' => array('yy' => 'titi'));
        $config = $class::mergeConfig($array1,$array2);
        $this->assertTrue(is_array($config));
        $this->assertEquals(count($array1)+count($array2), count($config));
        $this->assertEquals($array1['x'], $config['x']);
        $this->assertEquals($array2['y']['yy'], $config['y']['yy']);
    }

    public function test_encode()
	{
        $extension = new $this->myClass($this->ts);
        $value = 'toto';
        $result = $extension->encode($value);
        $this->assertEquals(array(), $result);
    }

    public function test_decode()
	{
        $extension = new $this->myClass($this->ts);
        $url = 'toto';
        $width = 10;
        $height = 20;
        $result = $extension->decode($url,$width,$height);
        $this->assertEquals('', $result);
    }

}
