<?php
require_once(__DIR__ . '/../../../../../init_new.php');

class OnlineTest extends \PHPUnit\Framework\TestCase
{
    public $myclass = 'Xoops\Core\Kernel\Handlers\XoopsOnline';

    protected function setUp()
    {
    }

    public function test___construct()
    {
        $instance = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $instance);
        $value = $instance->getVars();
        $this->assertTrue(isset($value['online_uid']));
        $this->assertTrue(isset($value['online_uname']));
        $this->assertTrue(isset($value['online_updated']));
        $this->assertTrue(isset($value['online_module']));
        $this->assertTrue(isset($value['online_ip']));
    }

    public function test_online_uid()
    {
        $instance = new $this->myclass();
        $value = $instance->online_uid();
        $this->assertNull($value);
    }

    public function test_online_uname()
    {
        $instance = new $this->myclass();
        $value = $instance->online_uname();
        $this->assertNull($value);
    }

    public function test_online_updated()
    {
        $instance = new $this->myclass();
        $value = $instance->online_updated();
        $this->assertNull($value);
    }

    public function test_online_module()
    {
        $instance = new $this->myclass();
        $value = $instance->online_module();
        $this->assertNull($value);
    }

    public function test_online_ip()
    {
        $instance = new $this->myclass();
        $value = $instance->online_ip();
        $this->assertNull($value);
    }
}
