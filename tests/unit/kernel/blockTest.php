<?php
require_once(__DIR__ . '/../init_new.php');

require_once(XOOPS_TU_ROOT_PATH . '/kernel/block.php');

class legacy_blockTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp()
    {
    }

    public function test___construct()
    {
        $instance = new \XoopsBlock();
        $this->assertInstanceOf('\Xoops\Core\Kernel\Handlers\XoopsBlock', $instance);
    }
}
