<?php
require_once(__DIR__ . '/../init_new.php');

require_once(XOOPS_TU_ROOT_PATH . '/kernel/configitem.php');

class legacy_configitemHandlerTest extends \PHPUnit\Framework\TestCase
{
    protected $myclass = 'XoopsConfigItemHandler';
    protected $conn = null;

    protected function setUp()
    {
        $this->conn = Xoops::getInstance()->db();
    }

    public function test___construct()
    {
        $instance = new \XoopsConfigItemHandler($this->conn);
        $this->assertInstanceOf('\Xoops\Core\Kernel\Handlers\XoopsConfigItemHandler', $instance);
    }
}
