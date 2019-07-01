<?php
require_once(__DIR__ . '/../../../../../init_new.php');

class BlockModuleLinkHandlerTest extends \PHPUnit\Framework\TestCase
{
    protected $myclass = 'Xoops\Core\Kernel\Handlers\XoopsBlockModuleLinkHandler';
    protected $conn = null;

    protected function setUp()
    {
        $this->conn = Xoops::getInstance()->db();
    }

    public function test___construct()
    {
        $instance = new $this->myclass($this->conn);
        $this->assertRegExp('/^.*system_blockmodule$/', $instance->table);
        $this->assertSame('\Xoops\Core\Kernel\Handlers\XoopsBlockModuleLink', $instance->className);
        $this->assertSame('block_id', $instance->keyName);
        $this->assertSame('module_id', $instance->identifierName);
    }

    public function testContracts()
    {
        $instance = new $this->myclass($this->conn);
        $this->assertInstanceOf('\Xoops\Core\Kernel\Handlers\XoopsBlockModuleLinkHandler', $instance);
        $this->assertInstanceOf('\Xoops\Core\Kernel\XoopsPersistableObjectHandler', $instance);
    }
}
