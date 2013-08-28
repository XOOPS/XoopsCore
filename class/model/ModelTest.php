<?php
require_once(dirname(__FILE__).'/../../init.php');

class ModelTest extends MY_UnitTestCase
{

    public function SetUp() {
    }

    public function test_100() {
        $instance=XoopsModelFactory::getInstance();
        $this->assertInstanceOf('XoopsModelFactory', $instance);
        $instance2=XoopsModelFactory::getInstance();
        $this->assertSame($instance,$instance2);
	}
}
