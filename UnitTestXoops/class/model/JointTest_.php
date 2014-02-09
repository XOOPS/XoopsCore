<?php
require_once(dirname(__FILE__).'/../../init.php');

require_once(XOOPS_ROOT_PATH . '/class/model/joint.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class JointTest extends MY_UnitTestCase
{
	protected $conn = null;

    public function SetUp()
	{	
		if (empty($this->conn)) {
			$this->conn = Xoops::getInstance()->db();
		}
    }

    public function test___construct() {
        $instance=new XoopsModelJoint();
        $this->assertInstanceOf('XoopsModelJoint', $instance);
        $this->assertInstanceOf('Xoops\Core\Kernel\XoopsModelAbstract', $instance);	
		$handler = new XoopsConfigItemHandler($this->conn);
		$result = $instance->setHandler($handler);
		$this->assertTrue($result);
	}
	
    public function test_setHandler()
	{
        $instance=new XoopsModelJoint();
        $this->assertinstanceOf('XoopsModelJoint', $instance);
		
		$handler = new XoopsGroupHandler($this->conn);
		$result = $instance->setHandler($handler);
		$this->assertTrue($result);
		
        $db = XoopsDatabaseFactory::getDatabaseConnection();
		$handler->table_link=$db->prefix('groups_users_link');
		$handler->field_link='groupid';
		
		$result = $instance->getByLink(null, null, true);
		$this->assertTrue(is_array($result) AND empty($result));
	}
	
    public function test_getByLink()
	{
	}

}
