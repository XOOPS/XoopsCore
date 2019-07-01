<?php
require_once(__DIR__ . '/../../../../../init_new.php');

class TplSetHandlerTest extends \PHPUnit\Framework\TestCase
{
    protected $myclass = 'Xoops\Core\Kernel\Handlers\XoopsTplSetHandler';
    protected $conn = null;

    protected function setUp()
    {
        $this->conn = Xoops::getInstance()->db();
    }

    public function test___construct()
    {
        $instance = new $this->myclass($this->conn);
        $this->assertRegExp('/^.*system_tplset$/', $instance->table);
        $this->assertSame('\Xoops\Core\Kernel\Handlers\XoopsTplSet', $instance->className);
        $this->assertSame('tplset_id', $instance->keyName);
        $this->assertSame('tplset_name', $instance->identifierName);
    }

    public function testContracts()
    {
        $instance = new $this->myclass($this->conn);
        $this->assertInstanceOf('\Xoops\Core\Kernel\Handlers\XoopsTplSetHandler', $instance);
        $this->assertInstanceOf('\Xoops\Core\Kernel\XoopsPersistableObjectHandler', $instance);
    }

    public function test_getByname()
    {
        $instance = new $this->myclass($this->conn);
        $value = $instance->getByname(1);
        $this->assertFalse($value);
    }

    public function test_getNameList()
    {
        $instance = new $this->myclass($this->conn);
        $value = $instance->getNameList();
        $this->assertInternalType('array', $value);
    }
}
