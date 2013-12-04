<?php
require_once(dirname(__FILE__).'/../init.php');
 
class CommentTest extends MY_UnitTestCase
{
    var $myclass='XoopsComment';
    
    public function SetUp() {
    }
    
    public function test_100() {
        $instance=new $this->myclass();
        $this->assertIsA($instance,$this->myclass);
		$value=$instance->getVars();
        $this->assertTrue(isset($value['com_id']));
        $this->assertTrue(isset($value['com_pid']));
        $this->assertTrue(isset($value['com_modid']));
        $this->assertTrue(isset($value['com_icon']));
        $this->assertTrue(isset($value['com_title']));
        $this->assertTrue(isset($value['com_text']));
        $this->assertTrue(isset($value['com_created']));
        $this->assertTrue(isset($value['com_modified']));
        $this->assertTrue(isset($value['com_uid']));
        $this->assertTrue(isset($value['com_ip']));
        $this->assertTrue(isset($value['com_sig']));
        $this->assertTrue(isset($value['com_itemid']));
        $this->assertTrue(isset($value['com_rootid']));
        $this->assertTrue(isset($value['com_status']));
        $this->assertTrue(isset($value['com_exparams']));
        $this->assertTrue(isset($value['dohtml']));
        $this->assertTrue(isset($value['dosmiley']));
        $this->assertTrue(isset($value['doxcode']));
        $this->assertTrue(isset($value['doimage']));
        $this->assertTrue(isset($value['dobr']));
    }

    public function test_120() {
        $instance=new $this->myclass();
        $value = $instance->id();
        $this->assertIdentical($value,null);
    }

    public function test_140() {
        $instance=new $this->myclass();
        $value = $instance->com_id();
        $this->assertIdentical($value,null);
    }

    public function test_160() {
        $instance=new $this->myclass();
        $value = $instance->com_pid();
        $this->assertIdentical($value,0);
    }

    public function test_180() {
        $instance=new $this->myclass();
        $value = $instance->com_modid();
        $this->assertIdentical($value,null);
    }

    public function test_200() {
        $instance=new $this->myclass();
        $value = $instance->com_icon();
        $this->assertIdentical($value,null);
    }

    public function test_220() {
        $instance=new $this->myclass();
        $value = $instance->com_title();
        $this->assertIdentical($value,null);
    }

    public function test_240() {
        $instance=new $this->myclass();
        $value = $instance->com_text();
        $this->assertIdentical($value,null);
    }
    
    public function test_260() {
        $instance=new $this->myclass();
        $value = $instance->com_created();
        $this->assertIdentical($value,0);
    }

    public function test_280() {
        $instance=new $this->myclass();
        $value = $instance->com_modified();
        $this->assertIdentical($value,0);
    }

    public function test_300() {
        $instance=new $this->myclass();
        $value = $instance->com_uid();
        $this->assertIdentical($value,0);
    }

    public function test_320() {
        $instance=new $this->myclass();
        $value = $instance->com_ip();
        $this->assertIdentical($value,null);
    }

    public function test_340() {
        $instance=new $this->myclass();
        $value = $instance->com_sig();
        $this->assertIdentical($value,0);
    }

    public function test_360() {
        $instance=new $this->myclass();
        $value = $instance->com_itemid();
        $this->assertIdentical($value,0);
    }    
}
