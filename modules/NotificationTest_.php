<?php
require_once(dirname(__FILE__).'/../init.php');

class NotificationTest extends MY_UnitTestCase
{
    var $myclass='XoopsNotification';

    public function SetUp() {
    }

    public function test_100() {
        $instance=new $this->myclass();
        $this->assertIsA($instance,$this->myclass);
		$value=$instance->getVars();
        $this->assertTrue(isset($value['not_id']));
        $this->assertTrue(isset($value['not_modid']));
        $this->assertTrue(isset($value['not_category']));
        $this->assertTrue(isset($value['not_itemid']));
        $this->assertTrue(isset($value['not_event']));
        $this->assertTrue(isset($value['not_uid']));
        $this->assertTrue(isset($value['not_mode']));
    }
    
    public function test_120() {
        $instance=new $this->myclass();
        $value=$instance->id();
        $this->assertIdentical($value,null);
    }
    
    public function test_140() {
        $instance=new $this->myclass();
        $value=$instance->not_id();
        $this->assertIdentical($value,null);
    }
    
    public function test_160() {
        $instance=new $this->myclass();
        $value=$instance->not_modid();
        $this->assertIdentical($value,null);
    }
    
    public function test_180() {
        $instance=new $this->myclass();
        $value=$instance->not_category();
        $this->assertIdentical($value,null);
    }
    
    public function test_200() {
        $instance=new $this->myclass();
        $value=$instance->not_itemid();
        $this->assertIdentical($value,0);
    }
    
    public function test_220() {
        $instance=new $this->myclass();
        $value=$instance->not_event();
        $this->assertIdentical($value,null);
    }
    
    public function test_240() {
        $instance=new $this->myclass();
        $value=$instance->not_uid();
        $this->assertIdentical($value,0);
    }
    
    public function test_260() {
        $instance=new $this->myclass();
        $value=$instance->not_mode();
        $this->assertIdentical($value,0);
    }
    
    public function test_280() {
        $instance=new $this->myclass();
        $value=$instance->notifyuser("template_dir", "template", "subject", array());
        $this->assertIdentical($value,false);
    }

}
