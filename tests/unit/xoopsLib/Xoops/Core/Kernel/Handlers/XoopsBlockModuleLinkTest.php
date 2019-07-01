<?php
require_once(__DIR__ . '/../../../../../init_new.php');

class BlockModuleLinkTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'Xoops\Core\Kernel\Handlers\XoopsBlockModuleLink';

    protected function setUp()
    {
    }

    public function test___construct()
    {
        $instance = new $this->myClass();
        $this->assertInstanceOf($this->myClass, $instance);
        $value = $instance->getVars();
        $this->assertTrue(isset($value['block_id']));
        $this->assertTrue(isset($value['module_id']));
    }

    public function test_getVar100()
    {
        $instance = new $this->myClass();
        $value = $instance->getVar('block_id', '');
        $this->assertNull($value);
    }

    public function test_getVar200()
    {
        $instance = new $this->myClass();
        $value = $instance->getVar('module_id', '');
        $this->assertNull($value);
    }
}
