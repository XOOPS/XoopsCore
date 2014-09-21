<?php
require_once(dirname(__DIR__) . '/init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class UserHandlerTest extends MY_UnitTestCase
{
    protected $myclass='XoopsUserHandler';
	protected $conn = null;

    public function SetUp()
	{
		$this->conn = Xoops::getInstance()->db();
    }

    public function test___construct()
	{
        $instance=new $this->myclass($this->conn);
        $this->assertInstanceOf($this->myclass,$instance);
		$this->assertRegExp('/^.*users$/',$instance->table);
		$this->assertSame('XoopsUser',$instance->className);
		$this->assertSame('uid',$instance->keyName);
		$this->assertSame('uname',$instance->identifierName);
    }

}
