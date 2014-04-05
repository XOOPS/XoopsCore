<?php
require_once(dirname(__FILE__).'/../../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Xoops_Cache_XcacheTest extends MY_UnitTestCase
{
    protected $myclass = 'Xoops_Cache_Xcache';
    
    public function test___construct()
	{
		$instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
		$this->assertInstanceOf('Xoops_Cache_Abstract', $instance);
    }
	
	public function test_init()
	{
		if (!extension_loaded('xcache')) $this->markTestSkipped();
		$this->markTestIncomplete();
	}
	
	public function test_write()
	{
		if (!extension_loaded('xcache')) $this->markTestSkipped();
		$this->markTestIncomplete();
	}
	
	public function test_read()
	{
		if (!extension_loaded('xcache')) $this->markTestSkipped();
		$this->markTestIncomplete();
	}
	
	public function test_increment()
	{
		if (!extension_loaded('xcache')) $this->markTestSkipped();
		$this->markTestIncomplete();
	}
	
	public function test_decrement()
	{
		if (!extension_loaded('xcache')) $this->markTestSkipped();
		$this->markTestIncomplete();
	}
	
	public function test_delete()
	{
		if (!extension_loaded('xcache')) $this->markTestSkipped();
		$this->markTestIncomplete();
	}
	
	public function test_clear()
	{
		if (!extension_loaded('xcache')) $this->markTestSkipped();
		$this->markTestIncomplete();
	}
	
	public function test_groups()
	{
		if (!extension_loaded('xcache')) $this->markTestSkipped();
		$this->markTestIncomplete();
	}
	
	public function test_clearGroup()
	{
		if (!extension_loaded('xcache')) $this->markTestSkipped();
		$this->markTestIncomplete();
	}
}
