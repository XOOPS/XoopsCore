<?php
require_once(dirname(__FILE__).'/../../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ModelTest extends MY_UnitTestCase
{

    public function SetUp() {
    }

    public function test_100() {
        $instance=XoopsModelFactory::getInstance();
        $this->assertInstanceOf('Xoops\Core\Kernel\XoopsModelFactory', $instance);
        $instance2=XoopsModelFactory::getInstance();
        $this->assertSame($instance,$instance2);
	}
}
