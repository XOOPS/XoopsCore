<?php
require_once(dirname(__FILE__).'/../init.php');

class ModuleHandlerTest extends MY_UnitTestCase
{
    var $myclass='XoopsModuleHandler';

    public function SetUp() {
    }

    public function test_100() {
        $instance=new $this->myclass();
        $this->assertInstanceOf($this->myclass,$instance);
		$this->assertRegExp('/^.*modules$/',$instance->table);
		$this->assertSame('XoopsModule',$instance->className);
		$this->assertSame('mid',$instance->keyName);
		$this->assertSame('dirname',$instance->identifierName);
    }

    public function test_120() {
        $instance=new $this->myclass();
        $value=$instance->getById();
        $this->assertSame(false,$value);
    }

    public function test_140() {
        $instance=new $this->myclass();
        $value=$instance->getByDirname('.');
        $this->assertSame(false,$value);
    }

    public function test_160() {
        $instance=new $this->myclass();
        $module=new XoopsModule();
        $module->setDirty(true);
        $value=$instance->insertModule($module);
        $this->assertSame(false,$value);
    }

    public function test_180() {
        $instance=new $this->myclass();
        $module=new XoopsModule();
        $instance->db->allowWebChanges=true;
        $value=$instance->deleteModule($module);
        $this->assertSame(true,$value);
    }

    public function test_200() {
        $instance=new $this->myclass();
        $value=$instance->getObjectsArray();
        $this->assertTrue(is_array($value));
    }

    public function test_220() {
        $instance=new $this->myclass();
        $value=$instance->getNameList();
        $this->assertTrue(is_array($value));
    }
}

