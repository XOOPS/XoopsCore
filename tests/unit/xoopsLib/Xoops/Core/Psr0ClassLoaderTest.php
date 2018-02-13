<?php
require_once(__DIR__.'/../../../init_new.php');

class Psr0ClassLoaderTest extends \PHPUnit\Framework\TestCase
{
    protected $myclass = 'Xoops\Core\Psr0ClassLoader';
	
    public function test___construct()
	{
		$instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
    }
	
    public function test_addLoader()
	{
		$this->markTestIncomplete();
    }
	
    public function test_setNamespaceSeparator()
	{
		$this->markTestIncomplete();
    }
	
    public function test_getNamespaceSeparator()
	{
		$this->markTestIncomplete();
    }
	
    public function test_setIncludePath()
	{
		$this->markTestIncomplete();
    }
	
    public function test_getIncludePath()
	{
		$this->markTestIncomplete();
    }
	
    public function test_setFileExtension()
	{
		$this->markTestIncomplete();
    }
	
    public function test_getFileExtension()
	{
		$this->markTestIncomplete();
    }
	
    public function test_register()
	{
		$this->markTestIncomplete();
    }
	
    public function test_unregister()
	{
		$this->markTestIncomplete();
    }
	
    public function test_loadClass()
	{
		$this->markTestIncomplete();
    }
}
