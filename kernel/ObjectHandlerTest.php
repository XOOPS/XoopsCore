<?php
require_once(dirname(__FILE__).'/../init.php');

class ObjecthandlerTest_XoopsObjectHandler extends XoopsObjectHandler
{
}

class ObjecthandlerTest_XoopsObject extends XoopsObject
{
}

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ObjecthandlerTest extends MY_UnitTestCase
{
    var $myclass='ObjecthandlerTest_XoopsObjectHandler';

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
		$object=new ObjecthandlerTest_XoopsObject();
		$instance->insert($object);
        $this->assertTrue(true);
    }
	
    public function test_180() {
		$db=XoopsDatabaseFactory::getDatabaseConnection();
        $instance=new $this->myclass($db);
		$object=new ObjecthandlerTest_XoopsObject();
		$instance->delete($object);
        $this->assertTrue(true);
    }
    
}
