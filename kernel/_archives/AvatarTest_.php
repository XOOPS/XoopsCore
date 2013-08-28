<?php
require_once(dirname(__FILE__).'/../init.php');
 
class AvatarTest extends MY_UnitTestCase
{
    
    public function SetUp() {
    }
    
    public function test_100() {
        $instance=new XoopsAvatar();
        $this->assertIsA($instance,'XoopsAvatar');
		$value=$instance->getVars();
        $this->assertTrue(isset($value['avatar_file']));
        $this->assertTrue(isset($value['avatar_name']));
        $this->assertTrue(isset($value['avatar_mimetype']));
        $this->assertTrue(isset($value['avatar_created']));
        $this->assertTrue(isset($value['avatar_display']));
        $this->assertTrue(isset($value['avatar_weight']));
        $this->assertTrue(isset($value['avatar_type']));
    }
    
    public function test_120() {
        $instance=new XoopsAvatar();
        $value = $instance->id();
        $this->assertIdentical($value,null);
    }
    
    public function test_140() {
        $instance=new XoopsAvatar();
        $value = $instance->avatar_id();
        $this->assertIdentical($value,null);
    }
    
    public function test_160() {
        $instance=new XoopsAvatar();
        $value = $instance->avatar_file();
        $this->assertIdentical($value,null);
    }
    
    public function test_180() {
        $instance=new XoopsAvatar();
        $value = $instance->avatar_name();
        $this->assertIdentical($value,null);
    }
    
    public function test_200() {
        $instance=new XoopsAvatar();
        $value = $instance->avatar_mimetype();
        $this->assertIdentical($value,null);
    }
    
    public function test_220() {
        $instance=new XoopsAvatar();
        $value = $instance->avatar_created();
        $this->assertIdentical($value,null);
    }
    
    public function test_240() {
        $instance=new XoopsAvatar();
        $value = $instance->avatar_display();
        $this->assertIdentical($value,1);
    }
    
    public function test_260() {
        $instance=new XoopsAvatar();
        $value = $instance->avatar_weight();
        $this->assertIdentical($value,0);
    }
    
    public function test_280() {
        $instance=new XoopsAvatar();
        $value = $instance->avatar_type();
        $this->assertIdentical($value,0);
    }
    
    public function test_300() {
        $instance=new XoopsAvatar();
        $value = $instance->getUserCount();
        $instance->setUserCount($value);        
        $ret = $instance->getUserCount();
        $this->assertEqual($value,$ret);
    }
}
