<?php
require_once(dirname(__FILE__).'/../init.php');

class UserHandlerTest extends MY_UnitTestCase
{
    var $myclass='XoopsUserHandler';

    public function SetUp() {
    }

    public function test_100() {
        $instance=new $this->myclass();
        $this->assertInstanceOf($this->myclass,$instance);
		$this->assertRegExp('/^.*users$/',$instance->table);
		$this->assertSame('XoopsUser',$instance->className);
		$this->assertSame('uid',$instance->keyName);
		$this->assertSame('uname',$instance->identifierName);
    }

}