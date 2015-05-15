<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ModuleHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass='XoopsModuleHandler';
	protected $conn = null;
	protected $mid = 0;

    public function setUp()
	{
		$this->conn = Xoops::getInstance()->db();
    }

    public function test___construct()
	{
        $instance=new $this->myclass($this->conn);
        $this->assertInstanceOf($this->myclass,$instance);
		$this->assertRegExp('/^.*modules$/',$instance->table);
		$this->assertSame('XoopsModule',$instance->className);
		$this->assertSame('mid',$instance->keyName);
		$this->assertSame('dirname',$instance->identifierName);
    }

    public function test_getById()
	{
        $instance=new $this->myclass($this->conn);
        $value=$instance->getById();
        $this->assertSame(false,$value);
    }

    public function test_getByDirname()
	{
        $instance=new $this->myclass($this->conn);
        $value=$instance->getByDirname('.');
        $this->assertSame(false,$value);
    }

    public function test_insertModule()
	{
        $instance=new $this->myclass($this->conn);
        $module=new XoopsModule();
        $module->setDirty(true);
        $module->setNew(true);
        $module->setVar('name', 'MODULE_DUMMY_FOR_TESTS', true);
        $value=$instance->insertModule($module);
        $this->assertTrue($value);
		
        $value=$instance->deleteModule($module);
        $this->assertTrue($value);
    }

    public function test_deleteModule()
	{
		// see test_insertModule
    }

    public function test_getObjectsArray()
	{
        $instance=new $this->myclass($this->conn);
        $value=$instance->getObjectsArray();
        $this->assertTrue(is_array($value));
    }

    public function test_getNameList()
	{
        $instance=new $this->myclass($this->conn);
        $value=$instance->getNameList();
        $this->assertTrue(is_array($value));
    }
}

