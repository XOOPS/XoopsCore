<?php
require_once(__DIR__ . '/../init_new.php');

require_once(XOOPS_TU_ROOT_PATH . '/kernel/tplfile.php');

class legacy_tplfileTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp()
    {
    }

    public function test___construct()
    {
        $instance = new \XoopsTplfile();
        $this->assertInstanceOf('\Xoops\Core\Kernel\Handlers\XoopsTplFile', $instance);
    }
}
