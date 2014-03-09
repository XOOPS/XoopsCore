<?php
require_once(dirname(__FILE__).'/../../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class YamlTest extends MY_UnitTestCase
{
    protected $myclass = 'Xoops\Core\Yaml';
	
    public function test___construct()
	{
		$instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
    }
	
    public function test_dump()
	{
		$this->markTestIncomplete();
    }
	
    public function test_load()
	{
		$this->markTestIncomplete();
    }
	
    public function test_read()
	{
		$this->markTestIncomplete();
    }
	
    public function test_save()
	{
		$this->markTestIncomplete();
    }

}
