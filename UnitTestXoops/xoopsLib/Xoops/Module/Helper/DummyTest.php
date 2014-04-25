<?php
require_once(dirname(__FILE__).'/../../../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Xoops_Module_Helper_DummyTest extends MY_UnitTestCase
{
    protected $myclass = '\Xoops\Module\Helper\Dummy';

    public function test___construct()
	{
		$instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
		$this->assertInstanceOf('\Xoops\Module\Helper\Dummy', $instance);
    }

    public function test_init()
	{
		$this->markTestIncomplete();
    }

    public function test_getInstance()
	{
		$this->markTestIncomplete();
    }

    public function test_setDirname()
	{
		$this->markTestIncomplete();
    }

	public function test_setDebug()
	{
		$this->markTestIncomplete();
    }

	public function test_addLog()
	{
		$this->markTestIncomplete();
    }
}
