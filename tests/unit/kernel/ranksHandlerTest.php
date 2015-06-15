<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class RanksHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass='XoopsRanksHandler';
    protected $object = null;

    public function setUp()
	{
		$conn = Xoops::getInstance()->db();
        $this->object=new $this->myclass($conn);
    }

    public function test___construct()
	{
        $instance = $this->object;
        $this->assertInstanceOf($this->myclass,$instance);
		$this->assertRegExp('/^.*ranks$/',$instance->table);
		$this->assertSame('XoopsRanks',$instance->className);
		$this->assertSame('rank_id',$instance->keyName);
		$this->assertSame('rank_title',$instance->identifierName);
    }
    
    public function test_setHandler()
	{
        $instance = $this->object;
        $value=$instance->setHandler();
        $this->assertSame(null,$value);
    }
    
    public function test_loadHandler()
	{
        $instance = $this->object;
        $value=$instance->loadHandler('write');
        $this->assertTrue(is_object($value));
    }
    
    public function test_create()
	{
        $instance = $this->object;
        $value=$instance->create(false);
        $this->assertInstanceOf('XoopsRanks',$value);
        $value=$instance->create(true);
        $this->assertInstanceOf('XoopsRanks',$value);
    }
    
    public function test_get()
	{
        $instance = $this->object;
        $value=$instance->get();
        $this->assertInstanceOf('XoopsRanks',$value);
    }
    
    public function test_insert()
	{
        $instance = $this->object;
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
        $instance = $this->object;
		$obj = new XoopsRanks();
		$obj->setDirty();
		$obj->setNew();
		$obj->setVar('rank_title','RANKTITLE_DUMMY_FOR_TESTS');
        $value = $instance->insert($obj);
        $this->assertTrue(intval($value) > 0);
        
        $criteria = new Criteria('rank_title', 'RANKTITLE_DUMMY_FOR_TESTS');
        $value = $instance->deleteAll($criteria);
        $this->assertTrue($value >= 1);
    }
    
    public function test_updateAll()
	{
        $instance = $this->object;
		$obj = new XoopsRanks();
		$obj->setDirty();
		$obj->setNew();
		$obj->setVar('rank_title','RANKTITLE_DUMMY_FOR_TESTS');
        $value = $instance->insert($obj);
        $this->assertTrue(intval($value) > 0);
        
        $criteria = new Criteria('rank_title', 'RANKTITLE_DUMMY_FOR_TESTS');
        $value=$instance->updateAll('rank_title','RANKTITLE_DUMMY_FOR_TESTS_after_updateAll', $criteria);
        $this->assertTrue($value >= 1);
        
        $criteria = new Criteria('rank_title', 'RANKTITLE_DUMMY_FOR_TESTS_after_updateAll');
        $value = $instance->deleteAll($criteria);
        $this->assertTrue($value >= 1);
    }
    
}