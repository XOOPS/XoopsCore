<?php
require_once(__DIR__ . '/../init_new.php');

require_once(XOOPS_TU_ROOT_PATH . '/kernel/config.php');

class legacy_configHandlerTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp()
    {
    }

    public function test___construct()
    {
        $instance = new \XoopsConfigHandler();
        $this->assertInstanceOf('\Xoops\Core\Kernel\Handlers\XoopsConfigHandler', $instance);
    }
}
