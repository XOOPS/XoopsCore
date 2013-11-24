<?php
require_once(dirname(__FILE__).'/../../init.php');

require_once(XOOPS_ROOT_PATH . '/class/model/xoopsmodel.php');
require_once(XOOPS_ROOT_PATH . '/class/model/read.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ReadTest extends MY_UnitTestCase
{

    public function SetUp() {
    }

    public function test_100() {
        $instance=new XoopsModelRead();
        $this->assertInstanceOf('XoopsModelRead', $instance);
        $this->assertInstanceOf('XoopsModelAbstract', $instance);
	}
}
