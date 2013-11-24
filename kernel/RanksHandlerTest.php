<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class RanksHandlerTest extends MY_UnitTestCase
{
    var $myclass='XoopsRanksHandler';

    public function SetUp() {
    }

    public function test_100() {
		$db=XoopsDatabaseFactory::getDatabaseConnection();
        $instance=new $this->myclass($db);
        $this->assertInstanceOf($this->myclass,$instance);
		$this->assertRegExp('/^.*ranks$/',$instance->table);
		$this->assertSame('XoopsRanks',$instance->className);
		$this->assertSame('rank_id',$instance->keyName);
		$this->assertSame('rank_title',$instance->identifierName);
    }
    
    public function test_120() {
		$db=XoopsDatabaseFactory::getDatabaseConnection();
        $instance=new $this->myclass($db);
        $value=$instance->setHandler();
        $this->assertSame(null,$value);
    }
    
    public function test_140() {
		$db=XoopsDatabaseFactory::getDatabaseConnection();
        $instance=new $this->myclass($db);
        $value=$instance->loadHandler('write');
        $this->assertTrue(is_object($value));
    }
    
    public function test_160() {
		$db=XoopsDatabaseFactory::getDatabaseConnection();
        $instance=new $this->myclass($db);
        $value=$instance->create(false);
        $this->assertInstanceOf('XoopsRanks',$value);
        $value=$instance->create(true);
        $this->assertInstanceOf('XoopsRanks',$value);
    }
    
    public function test_180() {
		$db=XoopsDatabaseFactory::getDatabaseConnection();
        $instance=new $this->myclass($db);
        $value=$instance->get();
        $this->assertInstanceOf('XoopsRanks',$value);
    }
    
    public function test_200() {
		$db=XoopsDatabaseFactory::getDatabaseConnection();
        $instance=new $this->myclass($db);
		$obj=new XoopsRanks();
		$obj->setDirty();
        $value=$instance->insert($obj);
        $this->assertSame('',$value);
    }
    
    public function test_220() {
		$db=XoopsDatabaseFactory::getDatabaseConnection();
        $instance=new $this->myclass($db);
		$obj=new XoopsRanks();
        $value=$instance->delete($obj);
        $this->assertSame(true,$value);
    }
    
    public function test_240() {
		$db=XoopsDatabaseFactory::getDatabaseConnection();
        $instance=new $this->myclass($db);
        $value=$instance->deleteAll();
		$this->markTestSkipped('');
        $this->assertSame(1,$value);
    }
    
    public function test_260() {
		$db=XoopsDatabaseFactory::getDatabaseConnection();
        $instance=new $this->myclass($db);
        $value=$instance->updateAll('name','value');
        $this->assertSame(false,$value);
    }
    
}