<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class PrivmessageHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass='XoopsPrivmessageHandler';
	protected $conn = null;

    public function setUp()
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
        $msg->setDirty(true);
        $msg->setNew(true);
        $msg->setVar('subject', 'PRIVMESSAGE_DUMMY_FOR_TESTS', true);
        $value=$instance->insert($msg);
        $this->assertTrue(intval($value) > 0);
		
        $value=$instance->setRead($msg);
        $this->assertSame(true,$value);
    }

}