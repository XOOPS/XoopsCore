<?php
require_once('../init.php');
//require_once(XOOPS_ROOT_PATH.'/class/registry.php');

class TestOfRegistry extends UnitTestCase
{
    protected $myclass = 'XoopsRegistry';

    public function SetUp() {
    }

    public function tearDown() {
		XoopsRegistry::_unsetInstance();
    }

    public function test_100() {
        $value = XoopsRegistry::getInstance();
        $this->assertIsA($value, $this->myclass);
        $value2 = XoopsRegistry::getInstance();
        $this->assertIdentical($value, $value2);
    }

    public function test_120() {
        $registry = new $this->myclass(); // Dont use getInstance there
        $this->assertIsA($registry, $this->myclass);
		XoopsRegistry::setInstance($registry);
        $this->assertIdentical($registry::$_registry, $registry);
		$this->expectError();
		XoopsRegistry::setInstance($registry);		
    }

}
