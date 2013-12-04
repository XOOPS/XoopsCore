<?php
require_once(dirname(__FILE__).'/../../../../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsModelFactoryTest extends MY_UnitTestCase
{
	protected $myClass = 'Xoops\Core\Kernel\XoopsModelFactory';

    public function SetUp()
	{
    }

    public function test_getInstance()
	{
		$class = $this->myClass;
        $instance = $class::getInstance();
        $this->assertInstanceOf($class, $instance);
		
        $instance2 = $class::getInstance();
        $this->assertSame($instance,$instance2);
	}
}
