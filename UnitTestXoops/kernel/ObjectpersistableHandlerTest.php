<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ObjectpersistableHandlerTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsGroupHandler'; // for example
	protected $conn = null;

    public function SetUp()
	{
		$this->conn = Xoops::getInstance()->db();
    }

    public function test___construct()
	{
        $instance=new $this->myclass($this->conn);
        $this->assertInstanceOf($this->myclass,$instance);
    }
    
    public function test_sethandler()
	{
        $instance=new $this->myclass($this->conn);
        $value=$instance->sethandler();
        $this->assertSame(null,$value);
    }
    
    public function test_loadhandler()
	{
        $instance=new $this->myclass($this->conn);
        $value=$instance->loadhandler('read');
        $this->assertTrue(is_object($value));
    }

    public function test_create()
	{
        $instance=new $this->myclass($this->conn);
        $value=$instance->create();
        $this->assertInstanceOf('XoopsGroup',$value);
    }
    
    public function test_get()
	{
        $instance=new $this->myclass($this->conn);
        $value=$instance->get();
        $this->assertInstanceOf('XoopsGroup',$value);
    }
    
    public function test_insert()
	{
        $instance=new $this->myclass($this->conn);
		$obj=new XoopsGroup();
		$obj->setDirty();
        $value=$instance->insert($obj);
        $this->assertSame('',$value);
    }
    
    public function test_delete()
	{
        $instance=new $this->myclass($this->conn);
		$obj=new XoopsGroup();
        $value=$instance->delete($obj);
        $this->assertSame(false,$value);
    }
    
    public function test_deleteAll()
	{
        $instance=new $this->myclass($this->conn);
        $value=$instance->deleteAll();
        $this->assertSame(0,$value);
    }
    
    public function test_updateAll()
	{
        $instance=new $this->myclass($this->conn);
        $value=$instance->updateAll('name','value');
        $this->assertSame(0,$value);
    }
    
    public function test_getObjects()
	{
        $instance=new $this->myclass($this->conn);
        $value=$instance->getObjects();
        $this->assertSame(array(),$value);
    }
    
    public function test_getAll()
	{
        $instance=new $this->myclass($this->conn);
        $value=$instance->getAll();
        $this->assertSame(array(),$value);
    }
    
    public function test_getList()
	{
        $instance=new $this->myclass($this->conn);
        $value=$instance->getList();
        $this->assertSame(array(),$value);
    }
    
    public function test_getIds()
	{
        $instance=new $this->myclass($this->conn);
        $value=$instance->getIds();
        $this->assertSame(array(),$value);
    }
    
    public function test_getCount()
	{
        $instance=new $this->myclass($this->conn);
        $value=$instance->getCount();
        $this->assertSame('0',$value);
    }
    
    public function test_getCounts()
	{
        $instance=new $this->myclass($this->conn);
        $value=$instance->getCounts();
        $this->assertTrue(is_array($value));
    }
    
    public function test_getByLink()
	{
        $instance=new $this->myclass($this->conn);
		$instance->field_object='groupid';
		$instance->table_link=$this->conn->prefix('group_permission');
		$instance->field_link='gperm_groupid';
        $value=$instance->getByLink();
        $this->assertTrue(is_array($value));
    }
    
    public function test_getCountByLink()
	{
        $instance=new $this->myclass($this->conn);
		$instance->keyName_link='gperm_name';
		$instance->field_object='groupid';
		$instance->table_link=$this->conn->prefix('group_permission');
		$instance->field_link='gperm_groupid';
        $value=$instance->getCountByLink();
        $this->assertSame('0',$value);
    }
    
    public function test_getCountsByLink()
	{
        $instance=new $this->myclass($this->conn);
		$instance->keyName_link='gperm_name';
		$instance->field_object='groupid';
		$instance->table_link=$this->conn->prefix('group_permission');
		$instance->field_link='gperm_groupid';
        $value=$instance->getCountsByLink();
        $this->assertTrue(is_array($value));
    }
    
    public function test_updateByLink()
	{
        $instance=new $this->myclass($this->conn);
		$instance->field_object='groupid';
		$instance->table_link=$this->conn->prefix('group_permission');
		$instance->field_link='gperm_groupid';
        $value=$instance->updateByLink(array('key'=>'value'));
        $this->assertSame(0,$value);
    }
    
    public function test_deleteByLink()
	{
        $instance=new $this->myclass($this->conn);
		$instance->field_object='groupid';
		$instance->table_link=$this->conn->prefix('group_permission');
		$instance->field_link='gperm_groupid';
        $value=$instance->deleteByLink();
        $this->assertSame(0,$value);
    }
    
    public function test_cleanOrphan()
	{
        $instance=new $this->myclass($this->conn);
        $value=$instance->cleanOrphan($this->conn->prefix('group_permission'),'gperm_groupid','groupid');
        $this->assertSame(0,$value);
    }
    
    public function test_synchronization()
	{
        $instance=new $this->myclass($this->conn);
        $value=$instance->synchronization();
        $this->assertSame(false,$value);
    }

}