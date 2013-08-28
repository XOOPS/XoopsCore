<?php
require_once(dirname(__FILE__).'/../init.php');

class OnlineHandlerTest extends MY_UnitTestCase
{
    protected $myclass='XoopsOnlineHandler';
	protected $myId = null;

    public function SetUp()
	{
    }

    public function test_100()
	{
        $instance=new $this->myclass();
        $this->assertInstanceOf($this->myclass,$instance);
		$this->assertRegExp('/^.*online$/',$instance->table);
		$this->assertSame('XoopsOnline',$instance->className);
		$this->assertSame('online_uid',$instance->keyName);
		$this->assertSame('online_uname',$instance->identifierName);
    }
    
	public function test_200()
	{
        $instance=new $this->myclass();
        $this->assertInstanceOf($this->myclass,$instance);
		
		$this->myId = (int)(microtime(true)%10000000);
		$value = $instance->write($this->myId, 'name', time(), 'module', 'localhost');
		$this->assertSame(true, $value);		
	}
	
	public function test_300()
	{
        $instance=new $this->myclass();
        $this->assertInstanceOf($this->myclass,$instance);
		
		$value = $instance->destroy($this->myId);
		$this->assertSame(true, $value);		
	}
	
	public function test_400()
	{
        $instance=new $this->myclass();
        $this->assertInstanceOf($this->myclass,$instance);
		
		$value = $instance->gc(time()+10);
		$this->assertSame(false, $value);		
	}
}