<?php
require_once(dirname(__FILE__).'/../init.php');

class ObjecthandlerTest extends MY_UnitTestCase
{
    var $myclass='XoopsObjectHandler';

    public function SetUp() {
    }

    public function test_100() {
		$db=XoopsDatabaseFactory::getDatabaseConnection();
        $instance=new $this->myclass($db);
        $this->assertInstanceOf($this->myclass,$instance);
    }
	
    public function test_120() {
		$db=XoopsDatabaseFactory::getDatabaseConnection();
        $instance=new $this->myclass($db);
		$instance->create();
        $this->assertTrue(true);
    }
	
    public function test_140() {
		$db=XoopsDatabaseFactory::getDatabaseConnection();
        $instance=new $this->myclass($db);
		$instance->get(1);
        $this->assertTrue(true);
    }
	
    public function test_160() {
		$db=XoopsDatabaseFactory::getDatabaseConnection();
        $instance=new $this->myclass($db);
		$object=new XoopsObject();
		$instance->insert($object);
        $this->assertTrue(true);
    }
	
    public function test_180() {
		$db=XoopsDatabaseFactory::getDatabaseConnection();
        $instance=new $this->myclass($db);
		$object=new XoopsObject();
		$instance->delete($object);
        $this->assertTrue(true);
    }
    
}
