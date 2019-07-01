<?php
require_once(__DIR__ . '/../../../../../init_new.php');

class MembershipHandlerTest extends \PHPUnit\Framework\TestCase
{
    protected $myclass = 'Xoops\Core\Kernel\Handlers\XoopsMembershipHandler';
    protected $conn = null;

    protected function setUp()
    {
        $this->conn = Xoops::getInstance()->db();
    }

    public function test___construct()
    {
        $instance = new $this->myclass($this->conn);
        $this->assertRegExp('/^.*system_usergroup$/', $instance->table);
        $this->assertSame('\Xoops\Core\Kernel\Handlers\XoopsMembership', $instance->className);
        $this->assertSame('linkid', $instance->keyName);
        $this->assertSame('groupid', $instance->identifierName);
    }

    public function testContracts()
    {
        $instance = new $this->myclass($this->conn);
        $this->assertInstanceOf('\Xoops\Core\Kernel\Handlers\XoopsMembershipHandler', $instance);
        $this->assertInstanceOf('\Xoops\Core\Kernel\XoopsPersistableObjectHandler', $instance);
    }

    public function test_getGroupsByUser()
    {
        $instance = new $this->myclass($this->conn);
        $value = $instance->getGroupsByUser(1);
        $this->assertInternalType('array', $value);
    }

    public function test_getGroupsByGroup()
    {
        $instance = new $this->myclass($this->conn);
        $value = $instance->getGroupsByGroup(1);
        $this->assertNull($value);
    }
}
