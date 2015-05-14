<?php
require_once(dirname(__FILE__).'/../../../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Psr0ClassLoaderTest extends \PHPUnit_Framework_TestCase
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
