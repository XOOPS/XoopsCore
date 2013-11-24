<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class GroupHandlerTest extends MY_UnitTestCase
{
    var $myclass='XoopsGroupHandler';
    
    public function SetUp() {
    }
    
    public function test_100() {
        $instance=new $this->myclass();
        $this->assertInstanceOf($this->myclass,$instance);
		$this->assertRegExp('/^.*groups$/',$instance->table);
		$this->assertSame('XoopsGroup',$instance->className);
		$this->assertSame('groupid',$instance->keyName);
		$this->assertSame('name',$instance->identifierName);
    }
    
}