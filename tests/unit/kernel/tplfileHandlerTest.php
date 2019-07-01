<?php
require_once(__DIR__ . '/../init_new.php');

require_once(XOOPS_TU_ROOT_PATH . '/kernel/tplfile.php');

class legacy_tplfileHandlerTest extends \PHPUnit\Framework\TestCase
{
    protected $conn = null;

    protected function setUp()
    {
        $this->conn = Xoops::getInstance()->db();
    }

    public function test___construct()
    {
        $instance = new \XoopsTplfileHandler($this->conn);
        $this->assertInstanceOf('\Xoops\Core\Kernel\Handlers\XoopsTplFileHandler', $instance);
    }
}
