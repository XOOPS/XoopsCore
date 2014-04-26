<?php
require_once(dirname(__FILE__).'/../init.php');

use Xoops\Core\Database\Connection;

class ObjecthandlerTest_XoopsObjectHandler extends XoopsObjectHandler
{
	function __construct(Connection $db)
	{
		parent::__construct($db);
	}
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
    protected $myclass='ObjecthandlerTest_XoopsObjectHandler';
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

    public function test_create()
	{
        $instance=new $this->myclass($this->conn);
		$instance->create();
        $this->assertTrue(true);
    }

    public function test_get()
	{
        $instance=new $this->myclass($this->conn);
		$instance->get(1);
        $this->assertTrue(true);
    }

    public function test_insert()
	{
        $instance=new $this->myclass($this->conn);
		$object=new ObjecthandlerTest_XoopsObject();
		$instance->insert($object);
        $this->assertTrue(true);
    }

    public function test_delete()
	{
        $instance=new $this->myclass($$this->conn);
		$object=new ObjecthandlerTest_XoopsObject();
		$instance->delete($object);
        $this->assertTrue(true);
    }

}
