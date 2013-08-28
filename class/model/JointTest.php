<?php
require_once(dirname(__FILE__).'/../../init.php');

require_once(XOOPS_ROOT_PATH . '/class/model/xoopsmodel.php');
require_once(XOOPS_ROOT_PATH . '/class/model/joint.php');

class JointTest extends MY_UnitTestCase
{

    public function SetUp() {
    }

    public function test_100() {
        $instance=new XoopsModelJoint();
        $this->assertInstanceOf('XoopsModelJoint', $instance);
        $this->assertInstanceOf('XoopsModelAbstract', $instance);
		
		$handler = new XoopsPersistableObjectHandler();
		$result = $instance->setHandler($handler);
		$this->assertTrue($result);
	}
	
    public function test_120() {
        $instance=new XoopsModelJoint();
        $this->assertinstanceOf('XoopsModelJoint', $instance);
		
		$handler = new XoopsGroupHandler();
		$result = $instance->setHandler($handler);
		$this->assertTrue($result);
		
        $db = XoopsDatabaseFactory::getDatabaseConnection();
		$handler->table_link=$db->prefix('groups_user_link');
		$handler->field_link='groupid';
		
		$result = $instance->getByLink(null, null, true);
		$this->assertTrue(is_array($result) AND empty($result));
	}

}
