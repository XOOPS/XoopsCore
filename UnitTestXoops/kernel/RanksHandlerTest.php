<?php
require_once(__DIR__.'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class RanksHandlerTest extends MY_UnitTestCase
{
    protected $myclass='XoopsRanksHandler';
	protected $conn = null;

    public function SetUp()
	{
		$this->conn = Xoops::getInstance()->db();
    }

    public function test___construct()
	{
        $instance=new $this->myclass($this->conn);
        $this->assertInstanceOf($this->myclass,$instance);
		$this->assertRegExp('/^.*ranks$/',$instance->table);
		$this->assertSame('XoopsRanks',$instance->className);
		$this->assertSame('rank_id',$instance->keyName);
		$this->assertSame('rank_title',$instance->identifierName);
    }
    
    public function test_setHandler()
	{
        $instance=new $this->myclass($this->conn);
        $value=$instance->setHandler();
        $this->assertSame(null,$value);
    }
    
    public function test_loadHandler()
	{
        $instance=new $this->myclass($this->conn);
        $value=$instance->loadHandler('write');
        $this->assertTrue(is_object($value));
    }
    
    public function test_create()
	{
        $instance=new $this->myclass($this->conn);
        $value=$instance->create(false);
        $this->assertInstanceOf('XoopsRanks',$value);
        $value=$instance->create(true);
        $this->assertInstanceOf('XoopsRanks',$value);
    }
    
    public function test_get()
	{
        $instance=new $this->myclass($this->conn);
        $value=$instance->get();
        $this->assertInstanceOf('XoopsRanks',$value);
    }
    
    public function test_insert()
	{
        $instance=new $this->myclass($this->conn);
		$obj=new XoopsRanks();
		$obj->setDirty();
		$obj->setNew();
		$obj->setVar('rank_title','RANKTITLE_DUMMY_FOR_TESTS');
        $value=$instance->insert($obj);
        $this->assertTrue(intval($value) > 0);
		
        $value=$instance->delete($obj);
        $this->assertTrue($value);
    }
    
    public function test_delete()
	{
		// see test_insert
    }
    
    public function test_deleteAll()
	{
        $instance=new $this->myclass($this->conn);
        $value=$instance->deleteAll();
		$this->markTestSkipped('');
        $this->assertSame(1,$value);
    }
    
    public function test_updateAll()
	{
        $instance=new $this->myclass($this->conn);
        $value=$instance->updateAll('name','value');
        $this->assertSame(0,$value);
    }
    
}