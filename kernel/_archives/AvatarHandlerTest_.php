<?php
require_once(dirname(__FILE__).'/../init.php');
	
class AvatarHandlerTest extends MY_UnitTestCase
{
    var $myclass='XoopsAvatarHandler';
    
    public function SetUp() {
    }
    
    public function test_100() {
        $instance=new $this->myclass();
        $this->assertIsA($instance,$this->myclass);
		$this->assertPattern('/^.*avatar$/',$instance->table);
		$this->assertIdentical($instance->className,'XoopsAvatar');
		$this->assertIdentical($instance->keyName,'avatar_id');
		$this->assertIdentical($instance->identifierName,'avatar_name');
    }
    
    public function test_120() {
        $instance=new $this->myclass();
        $value=$instance->getObjectsWithCount();
        $this->assertFalse($value);
    }
    
    public function test_140() {
        $instance=new $this->myclass();
        $value=$instance->addUser(1,1);
        $this->assertEqual($value,true);
    }
    
    public function test_160() {
        $instance=new $this->myclass();
        $avatar=new XoopsAvatar();
        $value=$instance->getUser($avatar);
        $this->assertFalse($value);
    }
    
    public function test_180() {
        $instance=new $this->myclass();
        $value=$instance->getListByType();
        $this->assertTrue(is_array($value));
    }
    
}