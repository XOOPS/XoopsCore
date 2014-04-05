<?php
require_once(dirname(__FILE__).'/../../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Xoops_Cache_FileTest extends MY_UnitTestCase
{
    protected $myclass = 'Xoops_Cache_File';
    
    public function test___construct()
	{
		$instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
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
	
	public function test_write()
	{
		$this->markTestIncomplete();
	}
	
	public function test_read()
	{
		$this->markTestIncomplete();
	}
	
	public function test_delete()
	{
		$this->markTestIncomplete();
	}
	
	public function test_clear()
	{
		$this->markTestIncomplete();
	}
	
	public function test_decrement()
	{
		$this->markTestIncomplete();
	}
	
	public function test_increment()
	{
		$this->markTestIncomplete();
	}
	
	public function test_key()
	{
		$this->markTestIncomplete();
	}
	
	public function test_clearGroup()
	{
		$this->markTestIncomplete();
	}
}
