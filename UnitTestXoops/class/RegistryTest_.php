<?php
require_once('../init.php');
//require_once(XOOPS_ROOT_PATH.'/class/registry.php');

class TestOfRegistry extends UnitTestCase
{
    protected $myClass = 'XoopsRegistry';

    public function SetUp() {
    }

    public function tearDown()
	{
		$class = $this->myClass;
		$class::_unsetInstance();
    }

    public function test_getInstance()
	{
		$class = $this->myClass;
        $value = $class::getInstance();
        $this->assertInstanceOf($class, $value);
        $value2 = $class::getInstance();
        $this->assertSame($value, $value2);
    }

    public function test_setInstance()
	{
		$class = $this->myClass;
        $registry = new $class(); // Dont use getInstance there
        $this->assertIsA($registry, $class);
		$class::setInstance($registry);
        $this->assertIdentical($registry::$_registry, $registry);
		$this->expectError();
		$class::setInstance($registry);		
    }

}
