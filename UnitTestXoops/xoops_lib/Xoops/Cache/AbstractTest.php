<?php
require_once(dirname(__FILE__).'/../../../init_mini.php');

class Xoops_Cache_AbstractInstance extends Xoops_Cache_Abstract
{
	function write($key, $value, $duration) {}
	function read($key) {}
	function increment($key, $offset = 1) {}
	function decrement($key, $offset = 1) {}
	function delete($key) {}
	function clear($check) {}
}

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Xoops_Cache_AbstractTest extends MY_UnitTestCase
{
    protected $myclass = 'Xoops_Cache_AbstractInstance';
    
    public function test___construct()
	{
		$instance = new $this->myclass();
		$this->assertInstanceOf('Xoops_Cache_Abstract', $instance);
    }
	
	public function test_init()
	{
		$this->markTestIncomplete();
	}
	
	public function test_gc()
	{
		$this->markTestIncomplete();
	}
	
	public function test_clearGroup()
	{
		$this->markTestIncomplete();
	}
	
	public function test_groups()
	{
		$this->markTestIncomplete();
	}
	
	public function test_settings()
	{
		$this->markTestIncomplete();
	}
	
	public function test_key()
	{
		$this->markTestIncomplete();
	}
	
	public function test_suspend()
	{
		$this->markTestIncomplete();
	}
	
	public function test_restore()
	{
		$this->markTestIncomplete();
	}
	
	public function test_resetpwd()
	{
		$this->markTestIncomplete();
	}
}
