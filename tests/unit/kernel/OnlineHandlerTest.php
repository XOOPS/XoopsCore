<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class OnlineHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass='XoopsOnlineHandler';
	protected $myId = null;
	protected $conn = null;

    public function setUp()
	{
		$this->conn = Xoops::getInstance()->db();
    }

    public function test___construct()
	{
        $instance=new $this->myclass($this->conn);
        $this->assertInstanceOf($this->myclass,$instance);
		$this->assertRegExp('/^.*online$/',$instance->table);
		$this->assertSame('XoopsOnline',$instance->className);
		$this->assertSame('online_uid',$instance->keyName);
		$this->assertSame('online_uname',$instance->identifierName);
    }
    
	public function test_write()
	{
        $instance=new $this->myclass($this->conn);
        $this->assertInstanceOf($this->myclass,$instance);
		
		$this->myId = (int)(microtime(true)%10000000);
		$value = $instance->write($this->myId, 'name', time(), 'module', 'localhost');
		$this->assertSame(true, $value);		
	}
	
	public function test_destroy()
	{
        $instance=new $this->myclass($this->conn);
        $this->assertInstanceOf($this->myclass,$instance);
		
		$value = $instance->destroy($this->myId);
		$this->assertSame(true, $value);		
	}
	
	public function test_gc()
	{
        $instance=new $this->myclass($this->conn);
        $this->assertInstanceOf($this->myclass,$instance);
		
		$value = $instance->gc(time()+10);
		$this->assertSame(true, $value);		
	}
}