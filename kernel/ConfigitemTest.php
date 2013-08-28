<?php
require_once(dirname(__FILE__).'/../init.php');

class ConfigItemTest extends MY_UnitTestCase
{
    var $myclass='XoopsConfigItem';

    public function SetUp() {
    }

    public function test_100() {
        $instance=new $this->myclass();
        $this->assertInstanceOf($this->myclass, $instance);
		$value=$instance->getVars();
		$this->assertTrue(isset($value['conf_id']));
		$this->assertTrue(isset($value['conf_modid']));
		$this->assertTrue(isset($value['conf_catid']));
		$this->assertTrue(isset($value['conf_name']));
		$this->assertTrue(isset($value['conf_title']));
		$this->assertTrue(isset($value['conf_value']));
		$this->assertTrue(isset($value['conf_desc']));
		$this->assertTrue(isset($value['conf_formtype']));
		$this->assertTrue(isset($value['conf_valuetype']));
		$this->assertTrue(isset($value['conf_order']));
    }

    public function test_120() {
        $instance=new $this->myclass();
        $value = $instance->id();
        $this->assertSame(null, $value);
    }
	
    public function test_140() {
        $instance=new $this->myclass();
        $value = $instance->conf_id();
        $this->assertSame(null, $value);
    }
	
    public function test_160() {
        $instance=new $this->myclass();
        $value = $instance->conf_modid();
        $this->assertSame(null, $value);
    }
	
    public function test_180() {
        $instance=new $this->myclass();
        $value = $instance->conf_catid();
        $this->assertSame(null, $value);
    }
	
    public function test_200() {
        $instance=new $this->myclass();
        $value = $instance->conf_name();
        $this->assertSame(null, $value);
    }
	
    public function test_220() {
        $instance=new $this->myclass();
        $value = $instance->conf_title();
        $this->assertSame(null, $value);
    }
	
    public function test_240() {
        $instance=new $this->myclass();
        $value = $instance->conf_value();
        $this->assertSame(null, $value);
    }
	
    public function test_260() {
        $instance=new $this->myclass();
        $value = $instance->conf_desc();
        $this->assertSame(null, $value);
    }
	
    public function test_280() {
        $instance=new $this->myclass();
        $value = $instance->conf_formtype();
        $this->assertSame(null, $value);
    }
	
    public function test_300() {
        $instance=new $this->myclass();
        $value = $instance->conf_valuetype();
        $this->assertSame(null, $value);
    }
	
    public function test_320() {
        $instance=new $this->myclass();
        $value = $instance->conf_order();
        $this->assertSame(null, $value);
    }
	
}
