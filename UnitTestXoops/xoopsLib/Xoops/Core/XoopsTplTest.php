<?php
require_once(dirname(__FILE__).'/../../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsTplTest extends MY_UnitTestCase
{
    protected $myclass = 'Xoops\Core\XoopsTpl';
	
    public function test___construct()
	{
		$instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
		$this->assertInstanceOf('SmartyBC', $instance);
    }
	
    public function test_fetchFromData()
	{
		$this->markTestIncomplete();
    }
	
    public function test_touch()
	{
		$this->markTestIncomplete();
    }
	
    public function test__get_auto_id()
	{
		$this->markTestIncomplete();
    }
	
    public function test_setCompileId()
	{
		$this->markTestIncomplete();
    }
	
    public function test_clearCache()
	{
		$this->markTestIncomplete();
    }

}
