<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class PrivmessageHandlerTest extends MY_UnitTestCase
{
    protected $myclass='XoopsPrivmessageHandler';
	protected $conn = null;

    public function SetUp()
	{
		$this->conn = Xoops::getInstance()->db();
    }

    public function test___construct()
	{
        $instance=new $this->myclass($this->conn);
        $this->assertInstanceOf($this->myclass,$instance);
		$this->assertRegExp('/^.*priv_msgs$/',$instance->table);
		$this->assertSame('XoopsPrivmessage',$instance->className);
		$this->assertSame('msg_id',$instance->keyName);
		$this->assertSame('subject',$instance->identifierName);
    }
    
    public function test_setRead()
	{
        $instance=new $this->myclass($this->conn);
		$msg=new XoopsPrivmessage();
        $value=$instance->setRead($msg);
        $this->assertSame(true,$value);
    }

}