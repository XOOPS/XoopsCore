<?php
require_once(dirname(__FILE__).'/../../../../init_mini.php');

class Xoops_Module_Helper_AbstractTestInstance extends Xoops\Module\Helper\HelperAbstract
{
}

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Xoops_Module_Helper_AbstractTest extends MY_UnitTestCase
{
    protected $myclass = 'Xoops_Module_Helper_AbstractTestInstance';

    public function test___construct()
	{
		$dir = XOOPS_ROOT_PATH.'/modules/avatar';
		$instance = new $this->myclass($dir);
		$this->assertInstanceOf($this->myclass, $instance);
    }

    public function test_init()
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

    public function test_getInstance()
	{
		$this->markTestIncomplete();
    }

	public function test_getModule()
	{
		$this->markTestIncomplete();
    }

	public function test_xoops()
	{
		$this->markTestIncomplete();
    }

    public function test_getConfig()
	{
		$this->markTestIncomplete();
    }

	public function test_getHandler()
	{
		$this->markTestIncomplete();
    }

    public function test_disableCache()
	{
		$this->markTestIncomplete();
    }

	public function test_isCurrentModule()
	{
		$this->markTestIncomplete();
    }

    public function test_isUserAdmin()
	{
		$this->markTestIncomplete();
    }

	public function test_getUserGroups()
	{
		$this->markTestIncomplete();
    }

    public function test_url()
	{
		$this->markTestIncomplete();
    }

	public function test_path()
	{
		$this->markTestIncomplete();
    }

    public function test_redirect()
	{
		$this->markTestIncomplete();
    }

	public function test_loadLanguage()
	{
		$this->markTestIncomplete();
    }

    public function test_loadLocale()
	{
		$this->markTestIncomplete();
    }

	public function test_getForm()
	{
		$this->markTestIncomplete();
    }

}
