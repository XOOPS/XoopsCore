<?php
require_once(__DIR__ . '/../../../../../init_new.php');

use Xoops\Core\Kernel\Criteria;
use Xoops\Core\Kernel\Handlers\XoopsBlock;
use Xoops\Core\Kernel\Handlers\XoopsBlockHandler;

class BlockHandlerTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'Xoops\Core\Kernel\Handlers\XoopsBlockHandler';
    protected $conn = null;
    protected $object;

    protected function setUp()
    {
        $this->conn = Xoops::getInstance()->db();
        $this->conn->setSafe();
        $this->object = new XoopsBlockHandler($this->conn);
    }

    public function test___construct()
    {
        $this->assertRegExp('/^.*system_block$/', $this->object->table);
        $this->assertSame('\Xoops\Core\Kernel\Handlers\XoopsBlock', $this->object->className);
        $this->assertSame('bid', $this->object->keyName);
        $this->assertNull($this->object->table_link);
        $this->assertSame('name', $this->object->identifierName);
        $this->assertNull($this->object->field_link);
        $this->assertNull($this->object->field_object);
    }

    public function testContracts()
    {
        $this->assertInstanceOf('\Xoops\Core\Kernel\Handlers\XoopsBlockHandler', $this->object);
        $this->assertInstanceOf('\Xoops\Core\Kernel\XoopsPersistableObjectHandler', $this->object);
    }

    public function test_insertBlock()
    {
        $block = new XoopsBlock();
        $block->setNew();
        $value = $this->object->insertBlock($block);
        $bid = $block->bid();
        $this->assertEquals($bid, $value);
        $value = $this->object->get($bid);
        $this->assertEquals($bid, $value->bid());
        $value = $this->object->deleteBlock($block);
        $this->assertTrue($value);
        $value = $this->object->get($bid);
        $this->assertNull($value);
    }

    public function test_getDistinctObjects()
    {
        $value = $this->object->getDistinctObjects();
        $this->assertInternalType('array', $value);
    }

    public function test_getDistinctObjects100()
    {
        $criteria = new Criteria('bid');
        $value = $this->object->getDistinctObjects($criteria);
        $this->assertInternalType('array', $value);
    }

    public function test_getNameList()
    {
        $value = $this->object->getNameList();
        $this->assertInternalType('array', $value);
    }

    public function test_getAllBlocksByGroup()
    {
        $value = $this->object->getAllBlocksByGroup(1);
        $this->assertInternalType('array', $value);

        $value = $this->object->getAllBlocksByGroup(1, false);
        $this->assertInternalType('array', $value);

        $value = $this->object->getAllBlocksByGroup([1, 1, 1], false);
        $this->assertInternalType('array', $value);

        $value = $this->object->getAllBlocksByGroup(1, true, XOOPS_SIDEBLOCK_BOTH);
        $this->assertInternalType('array', $value);

        $value = $this->object->getAllBlocksByGroup(1, true, XOOPS_CENTERBLOCK_ALL);
        $this->assertInternalType('array', $value);

        $value = $this->object->getAllBlocksByGroup(1, true, -1);
        $this->assertInternalType('array', $value);

        $value = $this->object->getAllBlocksByGroup(1, true, null, 1);
        $this->assertInternalType('array', $value);
    }

    public function test_getAllBlocks()
    {
        $value = $this->object->getAllBlocks();
        $this->assertInternalType('array', $value);

        $value = $this->object->getAllBlocks('object', true, XOOPS_SIDEBLOCK_BOTH);
        $this->assertInternalType('array', $value);

        $value = $this->object->getAllBlocks('object', true, XOOPS_CENTERBLOCK_ALL);
        $this->assertInternalType('array', $value);

        $value = $this->object->getAllBlocks('object', true, -1);
        $this->assertInternalType('array', $value);

        $value = $this->object->getAllBlocks('object', true, null, 1);
        $this->assertInternalType('array', $value);

        $value = $this->object->getAllBlocks('list');
        $this->assertInternalType('array', $value);

        $value = $this->object->getAllBlocks('id');
        $this->assertInternalType('array', $value);
    }

    public function test_getByModule()
    {
        $value = $this->object->getByModule('module');
        $this->assertInternalType('array', $value);

        $value = $this->object->getByModule('module', false);
        $this->assertInternalType('array', $value);
    }

    public function test_getAllByGroupModule()
    {
        $value = $this->object->getAllByGroupModule(1);
        $this->assertInternalType('array', $value);

        $value = $this->object->getAllByGroupModule([1, 1, 1]);
        $this->assertInternalType('array', $value);

        $value = $this->object->getAllByGroupModule(1, 1);
        $this->assertInternalType('array', $value);

        $value = $this->object->getAllByGroupModule(1, 1, true);
        $this->assertInternalType('array', $value);
    }

    public function test_getNonGroupedBlocks()
    {
        $value = $this->object->getNonGroupedBlocks();
        $this->assertInternalType('array', $value);

        $value = $this->object->getNonGroupedBlocks(1);
        $this->assertInternalType('array', $value);

        $value = $this->object->getNonGroupedBlocks(1, true);
        $this->assertInternalType('array', $value);

        $value = $this->object->getNonGroupedBlocks(0, false, true);
        $this->assertInternalType('array', $value);
    }

    public function test_countSimilarBlocks()
    {
        $value = $this->object->countSimilarBlocks(1, 1);
        $this->assertEquals(1, $value);

        $value = $this->object->countSimilarBlocks(1, 1, 'shows_func');
        $this->assertEquals(0, $value);
    }

    public function test_buildContent()
    {
        $value = $this->object->buildContent(0, 'titi', 'toto');
        $this->assertSame('tototiti', $value);

        $value = $this->object->buildContent(1, 'titi', 'toto');
        $this->assertSame('tititoto', $value);

        $value = $this->object->buildContent(2, 'titi', 'toto');
        $this->assertSame('', $value);
    }

    public function test_buildTitle()
    {
        $title = 'original';
        $value = $this->object->buildTitle($title);
        $this->assertEquals($title, $value);
        $title = 'original2';
        $new = 'new';
        $value = $this->object->buildTitle($title, $new);
        $this->assertEquals($new, $value);
    }

    public function test_getBlockByPerm()
    {
        $value = $this->object->getBlockByPerm(null);
        $this->assertTrue(empty($value) and is_array($value));

        $secondInstance = new XoopsBlockHandler($this->conn);
        $value = $secondInstance->getBlockByPerm(1);
        $this->assertInternalType('array', $value);
    }
}
