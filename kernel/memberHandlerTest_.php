<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class TestOfMemberHandler extends MY_UnitTestCase
{
    var $myclass='XoopsMemberHandler';
	
	var $user = null;
	var $group = null;

    public function SetUp() {
    }

    public function test_100() {
        $instance=new $this->myclass();
        $this->assertIsA($instance,$this->myclass);
    }

    public function test_120() {
        $instance=new $this->myclass();
        $this->group=$instance->createGroup();
        $this->assertIsA($this->group,'XoopsGroup');
    }

    public function test_140() {
        $instance=new $this->myclass();
        $this->user=$instance->createUser();
        $this->assertIsA($this->user,'XoopsUser');
    }

    public function test_160() {
        $instance=new $this->myclass();
        $value=$instance->getGroup($this->group->id());
        $this->assertIsA($value,'XoopsGroup');
    }
    
    public function test_180() {
        $instance=new $this->myclass();
        $value=$instance->getUser($this->user->id());
        $this->assertIsA($value,'XoopsUser');
    }
       
    public function test_240() {
        $instance=new $this->myclass();
		$this->group->setVar('name','name');
        $value=$instance->insertGroup($this->group);
        $this->assertTrue(is_numeric($value));
        $this->assertFalse($this->group->isNew());
    }
    
    public function test_250() {
        $instance=new $this->myclass();
		$this->user->setVar('name','name');
        $value=$instance->insertUser($this->user);
        $this->assertTrue(is_numeric($value));
        $this->assertFalse($this->user->isNew());
    }
    
    public function test_260() {
        $instance=new $this->myclass();
        $value=$instance->getGroups();
        $this->assertTrue(is_array($value));
    }
    
    public function test_280() {
        $instance=new $this->myclass();
        $value=$instance->getUsers();
        $this->assertTrue(is_array($value));
    }
    
    public function test_300() {
        $instance=new $this->myclass();
        $value=$instance->getGroupList();
        $this->assertTrue(is_array($value));
    }
    
    public function test_320() {
        $instance=new $this->myclass();
        $value=$instance->getUserList();
        $this->assertTrue(is_array($value));
    }
    
    public function test_360() {
        $instance=new $this->myclass();
        $group_id=$this->group->id();
        $user_id=$this->user->id();
        $value=$instance->addUserToGroup($group_id,$user_id);
        $this->assertTrue(is_numeric($value));
    }
    
    public function test_400() {
        $instance=new $this->myclass();
        $group_id=$this->group->id();
        $value=$instance->getUsersByGroup($group_id);
        $this->assertTrue(is_array($value));
    }
    
    public function test_420() {
        $instance=new $this->myclass();
        $user_id=$this->user->id();
        $value=$instance->getGroupsByUser($user_id);
        $this->assertTrue(is_array($value));
    }
    
    public function test_440() {
        $instance=new $this->myclass();
        $name='name';
        $pwd='pwd';
        $value=$instance->loginUser($name,$pwd);
        $this->assertIdentical($value,false);
     }
    
    public function test_460() {
        $instance=new $this->myclass();
        $name='name';
        $pwd='pwd';
        $value=$instance->loginUser($name,$pwd);
        $this->assertIdentical($value,false);
    }
    
    public function test_470() {
        $instance=new $this->myclass();
        $name='name';
        $pwd='pwd';
        $md5pwd=md5($pwd);
        $value=$instance->loginUserMd5($name,$md5pwd);
        $this->assertIdentical($value,false);
	}
	
    public function test_480() {
        $instance=new $this->myclass();
        $value=$instance->getUserCount();
        $this->assertTrue(is_string($value));
   }
    
    public function test_500() {
        $instance=new $this->myclass();
        $group_id=$this->group->id();
        $value=$instance->getUserCountByGroup($group_id);
        $this->assertTrue(is_string($value));
    }
    
    public function test_540() {
        $instance=new $this->myclass();
        $fieldname='name';
        $fieldvalue='name2';
        $value=$instance->updateUsersByField($fieldname,$fieldvalue);
        $this->assertIdentical($value,true);
    }

    public function test_560() {
        $instance=new $this->myclass();
        $this->assertFalse($this->user->isNew());		
        $value=$instance->activateUser($this->user);
        $this->assertTrue(is_numeric($value));
    }    
    
    public function test_580() {
        $instance=new $this->myclass();
        $value=$instance->getUsersByGroupLink(array(1,2,3));
        $this->assertTrue(is_array($value));
    }  
    
    public function test_600() {
        $instance=new $this->myclass();
        $value=$instance->getUserCountByGroupLink(array(1,2,3));
        $this->assertTrue(is_string($value));
    }
	
    
    public function test_650() {
        $instance=new $this->myclass();
        $group_id=$this->group->id();
        $user_id=$this->user->id();
        $value=$instance->removeUsersFromGroup($group_id,array($user_id));
        $this->assertTrue(is_numeric($value));
    }
	
    public function test_700() {
        $instance=new $this->myclass();
        $value=$instance->deleteGroup($this->group);
        $this->assertIdentical($value,true);
    }
    
    public function test_750() {
        $instance=new $this->myclass();
        $value=$instance->deleteUser($this->user);
        $this->assertIdentical($value,true);
    }
	
    public function test_9000() {
		$handler = Xoops::getInstance()->getHandlerUser();
		$sql = "ALTER TABLE `" . $handler->table . "` AUTO_INCREMENT = 0";
		$handler->db->queryF($sql);
		
		$handler = Xoops::getInstance()->getHandlerGroup();
		$sql = "ALTER TABLE `" . $handler->table . "` AUTO_INCREMENT = 0";
		$handler->db->queryF($sql);
		
		$handler = Xoops::getInstance()->getHandlerMembership();
		$sql = "ALTER TABLE `" . $handler->table . "` AUTO_INCREMENT = 0";
		$handler->db->queryF($sql);
    }
    
}
