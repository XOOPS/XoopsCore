<?php
require_once(__DIR__ . '/../../../../init_new.php');

use Xoops\Core\Kernel\Handlers\XoopsBlockHandler;
use Xoops\Core\Kernel\XoopsModelAbstract;

class XoopsModelAbstractTestInstance extends XoopsModelAbstract
{
    public function getHandler()
    {
        return $this->handler;
    }
}

class XoopsModelAbstractTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'XoopsModelAbstractTestInstance';

    public function test_setHandler()
    {
        $handler = new XoopsBlockHandler();

        $instance = new $this->myClass();
        $this->assertInstanceOf($this->myClass, $instance);
        $instance->setHandler($handler);
        $x = $instance->getHandler();
        $this->assertSame($handler, $x);
    }

    public function test_setVars()
    {
        $vars = ['one' => 1, 'two' => 2];
        $instance = new $this->myClass();
        $this->assertInstanceOf($this->myClass, $instance);
        $x = $instance->setVars($vars);
        $this->assertTrue($x);
        $this->assertTrue(!empty($instance->one));
        $this->assertTrue(1 == $instance->one);
        $this->assertTrue(!empty($instance->two));
        $this->assertTrue(2 == $instance->two);
    }
}
