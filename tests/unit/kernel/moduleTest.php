<?php
require_once(__DIR__ . '/../init_new.php');

require_once(XOOPS_TU_ROOT_PATH . '/kernel/module.php');

class legacy_moduleTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp()
    {
    }

    public function test___construct()
    {
        $instance = new \XoopsModule();
        $this->assertInstanceOf('\Xoops\Core\Kernel\Handlers\XoopsModule', $instance);
    }
}
