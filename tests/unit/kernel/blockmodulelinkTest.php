<?php
require_once(__DIR__ . '/../init_new.php');

require_once(XOOPS_TU_ROOT_PATH . '/kernel/blockmodulelink.php');

class legacy_blockmodulelinkTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp()
    {
    }

    public function test___construct()
    {
        $instance = new \XoopsBlockmodulelink();
        $this->assertInstanceOf('\Xoops\Core\Kernel\Handlers\XoopsBlockModuleLink', $instance);
    }
}
