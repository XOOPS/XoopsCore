<?php
require_once(dirname(__FILE__).'/../../init.php');

require_once(XOOPS_ROOT_PATH . '/class/model/xoopsmodel.php');
require_once(XOOPS_ROOT_PATH . '/class/model/write.php');

class WriteTest extends MY_UnitTestCase
{

    public function SetUp() {
    }

    public function test_100() {
        $instance=new XoopsModelWrite();
        $this->assertInstanceOf('XoopsModelWrite', $instance);
        $this->assertInstanceOf('XoopsModelAbstract', $instance);
	}
}
