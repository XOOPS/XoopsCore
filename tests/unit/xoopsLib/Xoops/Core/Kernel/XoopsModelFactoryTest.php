<?php
require_once(dirname(__FILE__).'/../../../../init_new.php');

use Xoops\Core\Kernel\Handlers\XoopsBlockHandler;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class xoopsmodelfactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $myClass = 'Xoops\Core\Kernel\XoopsModelFactory';

    public function setUp()
    {
    }

    public function test_getInstance()
    {
        $class = $this->myClass;
        $instance = $class::getInstance();
        $this->assertInstanceOf($class, $instance);

        $instance2 = $class::getInstance();
        $this->assertSame($instance, $instance2);
    }

    public function test_loadHandler()
    {
        $handler = new XoopsBlockHandler();
        $vars = array('one'=>1, 'two'=>2);

        $class = $this->myClass;
        $instance = $class::getInstance();
        $this->assertInstanceOf($class, $instance);

        $hdl = $instance->loadHandler($handler, 'read', $vars);
        $this->assertTrue(is_a($hdl, 'Xoops\Core\Kernel\Model\Read'));
        $this->assertTrue(is_a($hdl, 'Xoops\Core\Kernel\XoopsModelAbstract'));
        $this->assertTrue(!empty($hdl->one));
        $this->assertTrue($hdl->one == 1);
        $this->assertTrue(!empty($hdl->two));
        $this->assertTrue($hdl->two == 2);
    }
}
