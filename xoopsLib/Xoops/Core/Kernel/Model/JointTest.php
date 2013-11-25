<?php
require_once(dirname(__FILE__).'/../../../../../init.php');

use Xoops\Core\Kernel\XoopsModelAbstract;
use Xoops\Core\Kernel\Model\Joint;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class JointTest extends MY_UnitTestCase
{
	protected $conn = null;

    public function SetUp() {
		$db = XoopsDatabaseFactory::getDatabaseConnection();
		$this->conn = $db->conn;
    }

    public function test_100() {
        $instance=new Joint();
        $this->assertInstanceOf('Xoops\Core\Kernel\Model\Joint', $instance);
        $this->assertInstanceOf('Xoops\Core\Kernel\XoopsModelAbstract', $instance);	
		$handler = new XoopsConfigItemHandler($this->conn);
		$result = $instance->setHandler($handler);
		$this->assertTrue($result);
	}
	
    public function test_120() {
        $instance=new Joint();
        $this->assertinstanceOf('Xoops\Core\Kernel\Model\Joint', $instance);
		
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
