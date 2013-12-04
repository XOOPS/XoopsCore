<?php
require_once(dirname(__FILE__).'/../../../../../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class JointTest extends MY_UnitTestCase
{
	protected $conn = null;
	
	protected $myClass = 'Xoops\Core\Kernel\Model\Joint';
	protected $myAbstractClass = 'Xoops\Core\Kernel\XoopsModelAbstract';

    public function SetUp()
	{
		$db = XoopsDatabaseFactory::getDatabaseConnection();
		$this->conn = $db->conn;
    }

    public function test___construct()
	{
        $instance=new $this->myClass();
        $this->assertInstanceOf($this->myClass, $instance);
        $this->assertInstanceOf($this->myAbstractClass, $instance);	
	}
	
    public function test_setHandler()
	{
        $instance=new $this->myClass();
        $this->assertInstanceOf($this->myClass, $instance);
        $this->assertInstanceOf($this->myAbstractClass, $instance);	
		$handler = new XoopsConfigItemHandler($this->conn);
		$result = $instance->setHandler($handler);
		$this->assertTrue($result);
	}
	
    public function test_getByLink()
	{
        $instance=new $this->myClass();
        $this->assertinstanceOf($this->myClass, $instance);
		
		$handler = new XoopsGroupHandler($this->conn);
		$result = $instance->setHandler($handler);
		$this->assertTrue($result);
		
        $db = XoopsDatabaseFactory::getDatabaseConnection();
		$handler->table_link=$db->prefix('groups_users_link');
		$handler->field_link='groupid';
		
		$result = $instance->getByLink(null, null, true);
		$this->assertTrue(is_array($result) AND empty($result));
	}

}
