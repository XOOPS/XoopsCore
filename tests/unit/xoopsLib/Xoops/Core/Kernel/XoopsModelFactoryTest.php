<?php
require_once(__DIR__ . '/../../../../init_new.php');

use Xoops\Core\Kernel\Handlers\XoopsBlockHandler;

class XoopsModelFactoryTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'Xoops\Core\Kernel\XoopsModelFactory';

    protected function setUp()
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
        $vars = ['one' => 1, 'two' => 2];

        $class = $this->myClass;
        $instance = $class::getInstance();
        $this->assertInstanceOf($class, $instance);

        $hdl = $instance->loadHandler($handler, 'read', $vars);
        $this->assertTrue(is_a($hdl, 'Xoops\Core\Kernel\Model\Read'));
        $this->assertTrue(is_a($hdl, 'Xoops\Core\Kernel\XoopsModelAbstract'));
        $this->assertTrue(!empty($hdl->one));
        $this->assertTrue(1 == $hdl->one);
        $this->assertTrue(!empty($hdl->two));
        $this->assertTrue(2 == $hdl->two);
    }
}
