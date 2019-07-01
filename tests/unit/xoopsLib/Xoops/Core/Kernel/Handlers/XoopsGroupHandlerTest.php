<?php
require_once(__DIR__ . '/../../../../../init_new.php');

class GroupHandlerTest extends \PHPUnit\Framework\TestCase
{
    protected $myclass = 'Xoops\Core\Kernel\Handlers\XoopsGroupHandler';
    protected $conn = null;

    protected function setUp()
    {
        $this->conn = Xoops::getInstance()->db();
    }

    public function test___construct()
    {
        $instance = new $this->myclass($this->conn);
        $this->assertRegExp('/^.*system_group$/', $instance->table);
        $this->assertSame('\Xoops\Core\Kernel\Handlers\XoopsGroup', $instance->className);
        $this->assertSame('groupid', $instance->keyName);
        $this->assertSame('name', $instance->identifierName);
    }

    public function testContracts()
    {
        $instance = new $this->myclass($this->conn);
        $this->assertInstanceOf('\Xoops\Core\Kernel\Handlers\XoopsGroupHandler', $instance);
        $this->assertInstanceOf('\Xoops\Core\Kernel\XoopsPersistableObjectHandler', $instance);
    }
}
