<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class GrouppermHandlerTest extends MY_UnitTestCase
{
    var $myclass='XoopsGroupPermHandler';
    
    public function SetUp() {
    }
    
    public function test_100() {
        $instance=new $this->myclass();
        $this->assertInstanceOf($this->myclass,$instance);
		$this->assertRegExp('/^.*group_permission$/',$instance->table);
		$this->assertSame('XoopsGroupPerm',$instance->className);
		$this->assertSame('gperm_id',$instance->keyName);
		$this->assertSame('gperm_name',$instance->identifierName);
    }
    
    public function test_120() {
        $instance=new $this->myclass();
        $groupid=1;
        $value=$instance->deleteByGroup($groupid);
        $this->assertSame(1,$value);

        $value=$instance->deleteByGroup($groupid,1);
        $this->assertSame(0,$value);
    }
    
    public function test_140() {
        $instance=new $this->myclass();
        $modid=1;
        $value=$instance->deleteByModule($modid);
        $this->assertSame(0,$value);
		
        $value=$instance->deleteByModule($modid,'module');
        $this->assertSame(0,$value);
		
        $value=$instance->deleteByModule($modid,'module',1);
        $this->assertSame(0,$value);
    }
    
    public function test_160() {
        $instance=new $this->myclass();
        $name='name';
        $itemid=1;
        $groupid=1;
        $value=$instance->checkRight($name,$itemid,$groupid);
        $this->assertSame(true,$value);
		
        $value=$instance->checkRight($name,$itemid,$groupid,1,false);
        $this->assertSame(false,$value);
		
        $value=$instance->checkRight($name,$itemid,array($groupid,$groupid,$groupid));
        $this->assertSame(true,$value);
		
        $value=$instance->checkRight($name,$itemid,array($groupid,$groupid,$groupid), 1 , false);;
        $this->assertSame(false,$value);
    }
    
    public function test_180() {
        $instance=new $this->myclass();
        $name='name';
        $itemid=1;
        $groupid=1;
        $value=$instance->addRight($name,$itemid,$groupid);
        $this->assertTrue(is_numeric($value));
    }
    
    public function test_200() {
        $instance=new $this->myclass();
        $name='name';
        $groupid=1;
        $value=$instance->getItemIds($name,$groupid);
        $this->assertTrue(is_array($value));
		
        $value=$instance->getItemIds($name,array($groupid,$groupid,$groupid));
        $this->assertTrue(is_array($value));
    }
    
    public function test_220() {
        $instance=new $this->myclass();
        $name='name';
        $itemid=1;
        $value=$instance->getGroupIds($name,$itemid);
        $this->assertTrue(is_array($value));
    }
    
}