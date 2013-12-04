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

    public function SetUp()
	{
    }

    public function test___construct()
	{
        $instance=new $this->myclass();
        $this->assertIsA($instance,$this->myclass);
    }

    public function test_createGroup() {
        $instance=new $this->myclass();
        $this->group=$instance->createGroup();
        $this->assertIsA($this->group,'XoopsGroup');
    }

    public function test_createUser() {
        $instance=new $this->myclass();
        $this->user=$instance->createUser();
        $this->assertIsA($this->user,'XoopsUser');
    }

    public function test_getGroup() {
        $instance=new $this->myclass();
        $value=$instance->getGroup($this->group->id());
        $this->assertIsA($value,'XoopsGroup');
    }
    
    public function test_getUser() {
        $instance=new $this->myclass();
        $value=$instance->getUser($this->user->id());
        $this->assertIsA($value,'XoopsUser');
    }
       
    public function test_insertGroup() {
        $instance=new $this->myclass();
		$this->group->setVar('name','name');
        $value=$instance->insertGroup($this->group);
        $this->assertTrue(is_numeric($value));
        $this->assertFalse($this->group->isNew());
    }
    
    public function test_insertUser()
	{
        $instance=new $this->myclass();
		$this->user->setVar('name','name');
        $value=$instance->insertUser($this->user);
        $this->assertTrue(is_numeric($value));
        $this->assertFalse($this->user->isNew());
    }
    
    public function test_getGroups()
	{
        $instance=new $this->myclass();
        $value=$instance->getGroups();
        $this->assertTrue(is_array($value));
    }
    
    public function test_getUsers()
	{
        $instance=new $this->myclass();
        $value=$instance->getUsers();
        $this->assertTrue(is_array($value));
    }
    
    public function test_getGroupList()
	{
        $instance=new $this->myclass();
        $value=$instance->getGroupList();
        $this->assertTrue(is_array($value));
    }
    
    public function test_getUserList()
	{
        $instance=new $this->myclass();
        $value=$instance->getUserList();
        $this->assertTrue(is_array($value));
    }
    
    public function test_addUserToGroup()
	{
        $instance=new $this->myclass();
        $group_id=$this->group->id();
        $user_id=$this->user->id();
        $value=$instance->addUserToGroup($group_id,$user_id);
        $this->assertTrue(is_numeric($value));
    }
    
    public function test_getUsersByGroup()
	{
        $instance=new $this->myclass();
        $group_id=$this->group->id();
        $value=$instance->getUsersByGroup($group_id);
        $this->assertTrue(is_array($value));
    }
    
    public function test_getGroupsByUser()
	{
        $instance=new $this->myclass();
        $user_id=$this->user->id();
        $value=$instance->getGroupsByUser($user_id);
        $this->assertTrue(is_array($value));
    }
    
    public function test_loginUser()
	{
        $instance=new $this->myclass();
        $name='name';
        $pwd='pwd';
        $value=$instance->loginUser($name,$pwd);
        $this->assertIdentical($value,false);
     }

    public function test_loginUserMd5()
	{
        $instance=new $this->myclass();
        $name='name';
        $pwd='pwd';
        $md5pwd=md5($pwd);
        $value=$instance->loginUserMd5($name,$md5pwd);
        $this->assertIdentical($value,false);
	}
	
    public function test_getUserCount()
	{
        $instance=new $this->myclass();
        $value=$instance->getUserCount();
        $this->assertTrue(is_string($value));
   }
    
    public function test_getUserCountByGroup()
	{
        $instance=new $this->myclass();
        $group_id=$this->group->id();
        $value=$instance->getUserCountByGroup($group_id);
        $this->assertTrue(is_string($value));
    }
    
    public function test_updateUsersByField()
	{
        $instance=new $this->myclass();
        $fieldname='name';
        $fieldvalue='name2';
        $value=$instance->updateUsersByField($fieldname,$fieldvalue);
        $this->assertIdentical($value,true);
    }

    public function test_activateUser()
	{
        $instance=new $this->myclass();
        $this->assertFalse($this->user->isNew());		
        $value=$instance->activateUser($this->user);
        $this->assertTrue(is_numeric($value));
    }    
    
    public function test_getUsersByGroupLink()
	{
        $instance=new $this->myclass();
        $value=$instance->getUsersByGroupLink(array(1,2,3));
        $this->assertTrue(is_array($value));
    }  
    
    public function test_getUserCountByGroupLink()
	{
        $instance=new $this->myclass();
        $value=$instance->getUserCountByGroupLink(array(1,2,3));
        $this->assertTrue(is_string($value));
    }
	
    public function test_removeUsersFromGroup()
	{
        $instance=new $this->myclass();
        $group_id=$this->group->id();
        $user_id=$this->user->id();
        $value=$instance->removeUsersFromGroup($group_id,array($user_id));
        $this->assertTrue(is_numeric($value));
    }
	
    public function test_deleteGroup()
	{
        $instance=new $this->myclass();
        $value=$instance->deleteGroup($this->group);
        $this->assertIdentical($value,true);
    }
    
    public function test_deleteUser()
	{
        $instance=new $this->myclass();
        $value=$instance->deleteUser($this->user);
        $this->assertIdentical($value,true);
    }
	
    public function test_zzzz() {
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
