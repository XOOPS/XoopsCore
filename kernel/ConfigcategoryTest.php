<?php
require_once(dirname(__FILE__).'/../init.php');
 
class ConfigcategoryTest extends MY_UnitTestCase
{
    var $myclass='XoopsConfigCategory';
    
    public function SetUp() {
    }
    
    public function test_100() {
        $instance=new $this->myclass();
        $this->assertInstanceOf($this->myclass,$instance);
		$value=$instance->getVars();
        $this->assertTrue(isset($value['confcat_id']));
        $this->assertTrue(isset($value['confcat_name']));
        $this->assertTrue(isset($value['confcat_order']));
    }

    public function test_110() {
        $instance=new $this->myclass();
        $value = $instance->id();
        $this->assertSame(null, $value);
    }
    
    public function test_120() {
        $instance=new $this->myclass();
        $value = $instance->confcat_id();
        $this->assertSame(null, $value);
    }
    
    public function test_130() {
        $instance=new $this->myclass();
        $value = $instance->confcat_name('');
        $this->assertSame(null, $value);
    }
    
    public function test_140() {
        $instance=new $this->myclass();
        $value = $instance->confcat_order();
        $this->assertSame(0, $value);
    }
    
}
