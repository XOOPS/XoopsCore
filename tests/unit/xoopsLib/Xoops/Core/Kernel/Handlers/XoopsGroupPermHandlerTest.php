<?php
require_once(__DIR__ . '/../../../../../init_new.php');

use Xoops\Core\FixedGroups;

class GroupPermHandlerTest extends \PHPUnit\Framework\TestCase
{
    protected $myclass = 'Xoops\Core\Kernel\Handlers\XoopsGroupPermHandler';
    protected $conn = null;
    protected $name = 'name';
    protected $groupid = 9999;
    protected $modid = 9998;
    protected $itemid = 9997;

    protected function setUp()
    {
        $this->conn = Xoops::getInstance()->db();
    }

    public function test___construct()
    {
        $instance = new $this->myclass($this->conn);
        $this->assertRegExp('/^.*system_permission$/', $instance->table);
        $this->assertSame('\Xoops\Core\Kernel\Handlers\XoopsGroupPerm', $instance->className);
        $this->assertSame('gperm_id', $instance->keyName);
        $this->assertSame('gperm_name', $instance->identifierName);
    }

    public function testContracts()
    {
        $instance = new $this->myclass($this->conn);
        $this->assertInstanceOf('\Xoops\Core\Kernel\Handlers\XoopsGroupPermHandler', $instance);
        $this->assertInstanceOf('\Xoops\Core\Kernel\XoopsPersistableObjectHandler', $instance);
    }

    public function test_addRight()
    {
        $instance = new $this->myclass($this->conn);
        $name = $this->name;
        $groupid = $this->groupid;
        $itemid = $this->itemid;
        $modid = $this->modid;
        $value = $instance->addRight($name, $itemid, $groupid, $modid);
        $this->assertInternalType('numeric', $value);
    }

    public function test_checkRight()
    {
        $instance = new $this->myclass($this->conn);
        $name = $this->name;
        $groupid = $this->groupid;
        $itemid = $this->itemid;
        $modid = $this->modid;
        $value = $instance->checkRight($name, $itemid, $groupid, $modid, true);
        $this->assertTrue($value);

        $value = $instance->checkRight($name, $itemid, $groupid, $modid, false);
        $this->assertTrue($value);

        $value = $instance->checkRight($name, $itemid, [$groupid, $groupid, $groupid], $modid, true);
        $this->assertTrue($value);

        $value = $instance->checkRight($name, $itemid, [$groupid, $groupid, $groupid], $modid, false);
        $this->assertTrue($value);

        $value = $instance->checkRight('dummy', -1, null, -1);
        $this->assertFalse($value);

        $value = $instance->checkRight('dummy', -1, FixedGroups::ADMIN, -1);
        $this->assertTrue($value);

        $value = $instance->checkRight('dummy', [$groupid, $groupid, $groupid], FixedGroups::ADMIN, -1);
        $this->assertTrue($value);
    }

    public function test_getItemIds()
    {
        $instance = new $this->myclass($this->conn);
        $name = $this->name;
        $groupid = $this->groupid;
        $modid = $this->modid;
        $value = $instance->getItemIds($name, $groupid, $modid);
        $this->assertInternalType('array', $value);

        $value = $instance->getItemIds($name, [$groupid, $groupid, $groupid], $modid);
        $this->assertInternalType('array', $value);
    }

    public function test_getGroupIds()
    {
        $instance = new $this->myclass($this->conn);
        $name = $this->name;
        $itemid = $this->itemid;
        $modid = $this->modid;
        $value = $instance->getGroupIds($name, $itemid, $modid);
        $this->assertInternalType('array', $value);
    }

    public function test_deleteByGroup()
    {
        $instance = new $this->myclass($this->conn);
        $groupid = $this->groupid;
        $modid = $this->modid;
        $value = $instance->deleteByGroup($groupid, $modid);
        $this->assertTrue((int)($value) > 0);

        $name = $this->name;
        $groupid = $this->groupid;
        $itemid = $this->itemid;
        $modid = $this->modid;
        $value = $instance->addRight($name, $itemid, $groupid, $modid);
        $this->assertInternalType('numeric', $value);
        $value = $instance->deleteByGroup($groupid, $modid);
        $this->assertTrue((int)($value) > 0);

        $value = $instance->deleteByGroup($groupid, $modid);
        $this->assertSame(0, $value);
    }

    public function test_deleteByModule()
    {
        $this->markTestSkipped('Deletes ALL group permissions manged by system module!');
        $instance = new $this->myclass($this->conn);
        $name = $this->name;
        $groupid = $this->groupid;
        $itemid = $this->itemid;
        $modid = $this->modid;
        $value = $instance->addRight($name, $itemid, $groupid, $modid);
        $this->assertInternalType('numeric', $value);

        $value = $instance->deleteByModule($modid);
        $this->assertTrue((int)$value > 0);

        $value = $instance->addRight($name, $itemid, $groupid, $modid);
        $this->assertInternalType('numeric', $value);
        $value = $instance->deleteByModule($modid, $name);
        $this->assertTrue((int)$value > 0);

        $value = $instance->addRight($name, $itemid, $groupid, $modid);
        $this->assertInternalType('numeric', $value);
        $value = $instance->deleteByModule($modid, $name, $itemid);
        $this->assertTrue((int)$value > 0);

        $value = $instance->deleteByModule($modid, $name, $itemid);
        $this->assertSame(0, $value);
    }
}
