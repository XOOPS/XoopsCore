<?php
require_once(__DIR__.'/../../../../init.php');

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
	
    public function test_loadHandler()
	{
		$handler = new XoopsBlockHandler();
		$vars = array('one'=>1, 'two'=>2);
		
		$class = $this->myClass;
        $instance = $class::getInstance();
        $this->assertInstanceOf($class, $instance);
		
		$x = $instance->loadHandler($handler, 'read', $vars);
        $this->assertTrue(is_a($x,'Xoops\Core\Kernel\Model\Read'));
        $this->assertTrue(is_a($x,'Xoops\Core\Kernel\XoopsModelAbstract'));
        $this->assertTrue(!empty($x->one));
		$this->assertTrue($x->one == 1);
        $this->assertTrue(!empty($x->two));
		$this->assertTrue($x->two == 2);
    }
}
