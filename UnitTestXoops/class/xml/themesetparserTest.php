<?php
require_once(dirname(__FILE__).'/../../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsThemeSetParserTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsThemeSetParser';

    public function test___construct()
    {
		$input = 'input';
		$instance = new $this->myclass($input);
		$this->assertInstanceOf($this->myclass, $instance);
		$this->assertInstanceOf('SaxParser', $instance);
    }

    public function test_setThemeSetData()
    {
		$this->markTestIncomplete();
    }

    public function test_getThemeSetData()
    {
		$this->markTestIncomplete();
    }

    public function test_setImagesData()
    {
		$this->markTestIncomplete();
    }

    public function test_getImagesData()
    {
		$this->markTestIncomplete();
    }

    public function test_setTemplatesData()
    {
		$this->markTestIncomplete();
    }

    public function test_getTemplatesData()
    {
		$this->markTestIncomplete();
    }

    public function test_setTempArr()
    {
		$this->markTestIncomplete();
    }

    public function test_getTempArr()
    {
		$this->markTestIncomplete();
    }

    public function test_resetTempArr()
    {
		$this->markTestIncomplete();
    }
}