<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ConfigoptionTest extends \PHPUnit_Framework_TestCase
{
    var $myclass='XoopsConfigOption';
    
    public function setUp()
	{
    }
    
    public function test___construct()
	{
        $instance=new $this->myclass();
        $this->assertInstanceOf($this->myclass,$instance);
		$value=$instance->getVars();
        $this->assertTrue(isset($value['confop_id']));
        $this->assertTrue(isset($value['confop_name']));
        $this->assertTrue(isset($value['confop_value']));
        $this->assertTrue(isset($value['conf_id']));
    }

    public function test_id() {
        $instance=new $this->myclass();
        $value = $instance->id();
        $this->assertSame(null,$value);
    }
    
    public function test_confop_id() {
        $instance=new $this->myclass();
        $value = $instance->confop_id();
        $this->assertSame(null,$value);
    }
    
    public function test_confop_name() {
        $instance=new $this->myclass();
        $value = $instance->confop_name('');
        $this->assertSame(null,$value);
    }
    
    public function test_conf_id() {
        $instance=new $this->myclass();
        $value = $instance->conf_id();
        $this->assertSame(0,$value);
    }
	
    public function test_confop_value() {
        $instance=new $this->myclass();
        $value = $instance->confop_value();
        $this->assertSame(null,$value);
    }

}
