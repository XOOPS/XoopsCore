<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class MembershipHandlerTest extends MY_UnitTestCase
{
    var $myclass='XoopsMembershipHandler';

    public function SetUp() {
    }

    public function test_100() {
        $instance=new $this->myclass();
        $this->assertInstanceOf($this->myclass,$instance);
		$this->assertRegExp('/^.*groups_users_link$/',$instance->table);
		$this->assertSame('XoopsMembership',$instance->className);
		$this->assertSame('linkid',$instance->keyName);
		$this->assertSame('groupid',$instance->identifierName);
    }

    public function test_120() {
        $instance=new $this->myclass();
        $value=$instance->getGroupsByUser(1);
        $this->assertTrue(is_array($value));
    }
    
    public function test_140() {
        $instance=new $this->myclass();
        $value=$instance->getGroupsByGroup(1);
        $this->assertSame(null,$value);
    }
  
}

