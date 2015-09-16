<?php
require_once(dirname(__FILE__).'/../../../../../init_new.php');

use Xoops\Core\Kernel\Handlers\XoopsPrivmessageHandler;
use Xoops\Core\Kernel\Handlers\XoopsPrivmessage;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class PrivmessageHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass='Xoops\Core\Kernel\Handlers\XoopsPrivmessageHandler';
	protected $conn = null;

    public function setUp()
	{
		$this->conn = Xoops::getInstance()->db();
    }

    public function test___construct()
	{
        $instance=new $this->myclass($this->conn);
		$this->assertRegExp('/^.*priv_msgs$/',$instance->table);
		$this->assertSame('\\Xoops\\Core\\Kernel\\Handlers\XoopsPrivmessage',$instance->className);
		$this->assertSame('msg_id',$instance->keyName);
		$this->assertSame('subject',$instance->identifierName);
    }

    public function testContracts()
    {
        $instance=new $this->myclass($this->conn);
        $this->assertInstanceOf('\\Xoops\\Core\\Kernel\\Handlers\\XoopsPrivmessageHandler', $instance);
        $this->assertInstanceOf('\\Xoops\\Core\\Kernel\\XoopsPersistableObjectHandler', $instance);
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
