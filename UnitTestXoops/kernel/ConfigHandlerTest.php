<?php
require_once(__DIR__.'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ConfigHandlerTest extends MY_UnitTestCase
{
    var $myclass='XoopsConfigHandler';

    public function SetUp()
	{
    }

    public function test___construct()
	{
        $instance=new $this->myclass();
        $this->assertInstanceOf($this->myclass, $instance);
    }

    public function test_createConfig()
	{
        $instance=new $this->myclass();
        $value=$instance->createConfig();
        $this->assertInstanceOf('XoopsConfigItem', $value);
    }

    public function test_getConfig()
	{
        $instance=new $this->myclass();
        $value=$instance->getConfig(1);
        $this->assertInstanceOf('XoopsConfigItem', $value);
    }

    public function test_getConfig100()
	{
        $instance=new $this->myclass();
        $value=$instance->getConfig(1,true);
        $this->assertInstanceOf('XoopsConfigItem', $value);
    }

    public function test_insertConfig()
	{
        $instance=new $this->myclass();
        $item=new XoopsConfigItem();
        $item->setDirty();
        $ret=$instance->insertConfig($item);
        $this->assertSame(false, $ret);
    }

    public function test_deleteConfig()
	{
        $instance=new $this->myclass();
        $item=new XoopsConfigItem();
        $ret=$instance->deleteConfig($item);
		$this->markTestSkipped('');
        $this->assertSame(true, $ret);
    }

    public function test_getConfigs()
	{
        $instance=new $this->myclass();
        $ret=$instance->getConfigs();
        $this->assertTrue(is_array($ret));
    }

    public function test_getConfigCount()
	{
        $instance=new $this->myclass();
        $ret=$instance->getConfigCount();
        $this->assertTrue(is_numeric($ret));
    }

    public function test_getConfigsByCat()
	{
        $instance=new $this->myclass();
        $ret=$instance->getConfigsByCat(1);
        $this->assertTrue(is_array($ret));
    }

    public function test_createConfigOption()
	{
        $instance=new $this->myclass();
        $value=$instance->createConfigOption();
        $this->assertInstanceOf('XoopsConfigOption', $value);
    }

    public function test_getConfigOption()
	{
        $instance=new $this->myclass();
        $ret=$instance->getConfigOption(1);
        $this->assertTrue(is_object($ret));
    }

    public function test_getConfigOptions()
	{
        $instance=new $this->myclass();
        $ret=$instance->getConfigOptions();
        $this->assertTrue(is_array($ret));
    }

    public function test_getConfigOptionsCount()
	{
        $instance=new $this->myclass();
        $ret=$instance->getConfigOptionsCount();
        $this->assertTrue(is_numeric($ret));
    }

    public function test_getConfigList()
	{
        $instance=new $this->myclass();
        $ret=$instance->getConfigList(1);
        $this->assertTrue(is_array($ret));
    }
}
