<?php
require_once(dirname(__FILE__).'/../init.php');
 
class Module_MyTextSanitizerExtensionTest extends MY_UnitTestCase
{
    protected $myclass = 'MyTextSanitizerExtension';
    protected $ts = null;
    
    public function SetUp() {
        $this->ts = MyTextSanitizer::getInstance();
    }
    
    public function test_100() {
        $extension = new $this->myclass($this->ts);
        $this->assertInstanceOf($this->myclass, $extension);
        $this->assertEquals($this->ts, $extension->ts);
        $this->assertEquals(XOOPS_URL . '/images/form', $extension->image_path);
    }
    
    public function test_120() {
        $config = MyTextSanitizerExtension::loadConfig();
        $this->assertTrue(is_array($config));
    }
    
    public function test_140() {
        $array1 = array('x' => 'toto');
        $array2 = array('y' => 'titi');
        $config = MyTextSanitizerExtension::mergeConfig($array1,$array2);
        $this->assertTrue(is_array($config));
        $this->assertEquals(count($array1)+count($array2), count($config));
        $this->assertEquals($array1['x'], $config['x']);
        $this->assertEquals($array2['y'], $config['y']);
    }
    
    public function test_160() {
        $array1 = array('x' => 'toto');
        $array2 = array('y' => array('yy' => 'titi'));
        $config = MyTextSanitizerExtension::mergeConfig($array1,$array2);
        $this->assertTrue(is_array($config));
        $this->assertEquals(count($array1)+count($array2), count($config));
        $this->assertEquals($array1['x'], $config['x']);
        $this->assertEquals($array2['y']['yy'], $config['y']['yy']);
    }

    public function test_180() {
        $extension = new $this->myclass($this->ts);
        $value = 'toto';
        $result = $extension->encode($value);
        $this->assertEquals(array(), $result);
    }
    
    public function test_200() {
        $extension = new $this->myclass($this->ts);
        $url = 'toto';
        $width = 10;
        $height = 20;
        $result = $extension->decode($url,$width,$height);
        $this->assertEquals('', $result);
    }
    
}
