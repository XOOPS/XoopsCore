<?php
require_once(dirname(__FILE__).'/../init.php');

class ConfigHandlerTest extends MY_UnitTestCase
{
    var $myclass='XoopsConfigHandler';

    public function SetUp() {
    }

    public function test_100() {
        $instance=new $this->myclass();
        $this->assertInstanceOf($this->myclass, $instance);
    }

    public function test_110() {
        $instance=new $this->myclass();
        $value=$instance->createConfig();
        $this->assertInstanceOf('XoopsConfigItem', $value);
    }

    public function test_120() {
        $instance=new $this->myclass();
        $value=$instance->getConfig(1);
        $this->assertInstanceOf('XoopsConfigItem', $value);
    }

    public function test_130() {
        $instance=new $this->myclass();
        $value=$instance->getConfig(1,true);
        $this->assertInstanceOf('XoopsConfigItem', $value);
    }

    public function test_140() {
        $instance=new $this->myclass();
        $item=new XoopsConfigItem();
        $item->setDirty();
        $ret=$instance->insertConfig($item);
        $this->assertSame(false, $ret);
    }

    public function test_150() {
        $instance=new $this->myclass();
        $item=new XoopsConfigItem();
        $ret=$instance->deleteConfig($item);
		$this->markTestSkipped('');
        $this->assertSame(true, $ret);
    }

    public function test_160() {
        $instance=new $this->myclass();
        $ret=$instance->getConfigs();
        $this->assertTrue(is_array($ret));
    }

    public function test_170() {
        $instance=new $this->myclass();
        $ret=$instance->getConfigCount();
        $this->assertTrue(is_numeric($ret));
    }

    public function test_180() {
        $instance=new $this->myclass();
        $ret=$instance->getConfigCount();
        $this->assertTrue(is_numeric($ret));
    }

    public function test_190() {
        $instance=new $this->myclass();
        $ret=$instance->getConfigsByCat(1);
        $this->assertTrue(is_array($ret));
    }

    public function test_200() {
        $instance=new $this->myclass();
        $value=$instance->createConfigOption();
        $this->assertInstanceOf('XoopsConfigOption', $value);
    }

    public function test_210() {
        $instance=new $this->myclass();
        $ret=$instance->getConfigOption(1);
        $this->assertTrue(is_object($ret));
    }

    public function test_220() {
        $instance=new $this->myclass();
        $ret=$instance->getConfigOptions();
        $this->assertTrue(is_array($ret));
    }

    public function test_230() {
        $instance=new $this->myclass();
        $ret=$instance->getConfigOptionsCount();
        $this->assertTrue(is_numeric($ret));
    }

    public function test_240() {
        $instance=new $this->myclass();
        $ret=$instance->getConfigList(1);
        $this->assertTrue(is_array($ret));
    }
}
