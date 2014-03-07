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
		$instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
		$this->assertInstanceOf('SaxParser', $instance);
    }

    public function test_setThemeSetData()
    {
    }

    public function test_getThemeSetData()
    {
    }

    public function test_setImagesData()
    {
    }

    public function test_getImagesData()
    {
    }

    public function test_setTemplatesData()
    {
    }

    public function test_getTemplatesData()
    {
    }

    public function test_setTempArr()
    {
    }

    public function test_getTempArr()
    {
    }

    public function test_resetTempArr()
    {
    }
}