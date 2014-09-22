<?php
require_once(dirname(__DIR__) . '/init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class GroupHandlerTest extends MY_UnitTestCase
{
    protected $myclass='XoopsGroupHandler';
	protected $conn = null;

    public function SetUp()
	{
		$this->conn = Xoops::getInstance()->db();
    }

    public function test___construct()
	{
        $instance=new $this->myclass($this->conn);
        $this->assertInstanceOf($this->myclass,$instance);
		$this->assertRegExp('/^.*groups$/',$instance->table);
		$this->assertSame('XoopsGroup',$instance->className);
		$this->assertSame('groupid',$instance->keyName);
		$this->assertSame('name',$instance->identifierName);
    }

}
