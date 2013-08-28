<?php
require_once(dirname(__FILE__).'/../init.php');
 
class ConfigoptionTest extends MY_UnitTestCase
{
    var $myclass='XoopsConfigOption';
    
    public function SetUp() {
    }
    
    public function test_100() {
        $instance=new $this->myclass();
        $this->assertInstanceOf($this->myclass,$instance);
		$value=$instance->getVars();
        $this->assertTrue(isset($value['confop_id']));
        $this->assertTrue(isset($value['confop_name']));
        $this->assertTrue(isset($value['confop_value']));
        $this->assertTrue(isset($value['conf_id']));
    }

    public function test_110() {
        $instance=new $this->myclass();
        $value = $instance->id();
        $this->assertSame(null,$value);
    }
    
    public function test_120() {
        $instance=new $this->myclass();
        $value = $instance->confop_id();
        $this->assertSame(null,$value);
    }
    
    public function test_130() {
        $instance=new $this->myclass();
        $value = $instance->confop_name('');
        $this->assertSame(null,$value);
    }
    
    public function test_140() {
        $instance=new $this->myclass();
        $value = $instance->conf_id();
        $this->assertSame(0,$value);
    }
	
    public function test_150() {
        $instance=new $this->myclass();
        $value = $instance->confop_value();
        $this->assertSame(null,$value);
    }

}
