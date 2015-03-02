<?php
require_once(dirname(__FILE__).'/../../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Xoops_Cache_MemcacheTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'Xoops_Cache_Memcache';
    
    public function test___construct()
	{
		$instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
		$this->assertInstanceOf('Xoops_Cache_Abstract', $instance);
		
		$items = array('settings');
		foreach ($items as $item) {
			$property = new ReflectionProperty($this->myclass, $item);
			$this->assertTrue($property->isPublic());
		}
    }
	
	public function test_init()
	{
		if (!extension_loaded('memcache')) $this->markTestSkipped();
		$this->markTestIncomplete();
	}
	
	public function test_gc()
	{
		if (!extension_loaded('memcache')) $this->markTestSkipped();
		$this->markTestIncomplete();
	}
	
	public function test_write()
	{
		if (!extension_loaded('memcache')) $this->markTestSkipped();
		$this->markTestIncomplete();
	}
	
	public function test_read()
	{
		if (!extension_loaded('memcache')) $this->markTestSkipped();
		$this->markTestIncomplete();
	}
	
	public function test_delete()
	{
		if (!extension_loaded('memcache')) $this->markTestSkipped();
		$this->markTestIncomplete();
	}
	
	public function test_clear()
	{
		if (!extension_loaded('memcache')) $this->markTestSkipped();
		$this->markTestIncomplete();
	}
	
	public function test_decrement()
	{
		if (!extension_loaded('memcache')) $this->markTestSkipped();
		$this->markTestIncomplete();
	}
	
	public function test_increment()
	{
		if (!extension_loaded('memcache')) $this->markTestSkipped();
		$this->markTestIncomplete();
	}
	
	public function test_key()
	{
		if (!extension_loaded('memcache')) $this->markTestSkipped();
		$this->markTestIncomplete();
	}
	
	public function test_clearGroup()
	{
		if (!extension_loaded('memcache')) $this->markTestSkipped();
		$this->markTestIncomplete();
	}
}
