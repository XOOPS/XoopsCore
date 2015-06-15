<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class BlockHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass='XoopsBlockHandler';
	protected $conn = null;
    
    public function setUp()
	{
		$this->conn = Xoops::getInstance()->db();
    }
    
    public function test___construct()
	{
        $instance = new XoopsBlockHandler($this->conn);
        $this->assertInstanceOf('XoopsBlockHandler',$instance);
		$this->assertRegExp('/^.*newblocks$/',$instance->table);
		$this->assertSame('bid',$instance->keyName);
		$this->assertSame('XoopsBlock',$instance->className);
		$this->assertSame(null,$instance->table_link);
		$this->assertSame('name',$instance->identifierName);
		$this->assertSame(null,$instance->field_link);
		$this->assertSame(null,$instance->field_object);
		$this->assertSame(null,$instance->keyName_link);
    }
    
    public function test_insertBlock()
	{
        $block = new XoopsBlock();
		$block->setNew();
        $instance = new XoopsBlockHandler($this->conn);
        $value = $instance->insertBlock($block);
		$bid = $block->bid();
        $this->assertEquals($bid,$value);
		$value = $instance->get($bid);
        $this->assertEquals($bid,$value->bid());
        $value = $instance->deleteBlock($block);
        $this->assertSame(true,$value);
		$value = $instance->get($bid);
        $this->assertSame(null,$value);
    }
	
    public function test_deleteBlock()
	{
	}
    
    public function test_getDistinctObjects()
	{
        $instance = new XoopsBlockHandler($this->conn);
        $value = $instance->getDistinctObjects();
        $this->assertTrue(is_array($value));
    }
    
    public function test_getDistinctObjects100()
	{
        $instance = new XoopsBlockHandler($this->conn);
		$criteria = new Criteria('bid');
        $value = $instance->getDistinctObjects($criteria);
        $this->assertTrue(is_array($value));
    }
	
    public function test_getNameList()
	{
        $instance = new XoopsBlockHandler($this->conn);
        $value = $instance->getNameList();
        $this->assertTrue(is_array($value));
    }
    
    public function test_getAllBlocksByGroup()
	{
        $instance = new XoopsBlockHandler($this->conn);
        $value = $instance->getAllBlocksByGroup(1);
        $this->assertTrue(is_array($value));
		
        $value = $instance->getAllBlocksByGroup(1, false);
        $this->assertTrue(is_array($value));
		
        $value = $instance->getAllBlocksByGroup(array(1,1,1), false);
        $this->assertTrue(is_array($value));
		
        $value = $instance->getAllBlocksByGroup(1, true, XOOPS_SIDEBLOCK_BOTH);
        $this->assertTrue(is_array($value));
		
        $value = $instance->getAllBlocksByGroup(1, true, XOOPS_CENTERBLOCK_ALL);
        $this->assertTrue(is_array($value));
		
        $value = $instance->getAllBlocksByGroup(1, true, -1);
        $this->assertTrue(is_array($value));
		
        $value = $instance->getAllBlocksByGroup(1, true, null, 1);
        $this->assertTrue(is_array($value));
    }
    
    public function test_getAllBlocks()
	{
        $instance = new XoopsBlockHandler($this->conn);
        $value = $instance->getAllBlocks();
        $this->assertTrue(is_array($value));
		
        $value = $instance->getAllBlocks('object', true, XOOPS_SIDEBLOCK_BOTH);
        $this->assertTrue(is_array($value));
		
        $value = $instance->getAllBlocks('object', true, XOOPS_CENTERBLOCK_ALL);
        $this->assertTrue(is_array($value));
		
        $value = $instance->getAllBlocks('object', true, -1);
        $this->assertTrue(is_array($value));
		
        $value = $instance->getAllBlocks('object', true, null, 1);
        $this->assertTrue(is_array($value));
		
        $value = $instance->getAllBlocks('list');
        $this->assertTrue(is_array($value));
		
        $value = $instance->getAllBlocks('id');
        $this->assertTrue(is_array($value));
    }
    
    public function test_getByModule()
	{
        $instance=new XoopsBlockHandler($this->conn);
        $value = $instance->getByModule('module');
        $this->assertTrue(is_array($value));
		
        $value = $instance->getByModule('module', false);
        $this->assertTrue(is_array($value));
    }
    
    public function test_getAllByGroupModule()
	{
        $instance = new XoopsBlockHandler($this->conn);
        $value = $instance->getAllByGroupModule(1);
        $this->assertTrue(is_array($value));
		
        $value = $instance->getAllByGroupModule(array(1,1,1));
        $this->assertTrue(is_array($value));
		
        $value = $instance->getAllByGroupModule(1, 1);
        $this->assertTrue(is_array($value));
		
        $value = $instance->getAllByGroupModule(1, 1, true);
        $this->assertTrue(is_array($value));
    }
    
    public function test_getNonGroupedBlocks()
	{
        $instance = new XoopsBlockHandler($this->conn);
        $value = $instance->getNonGroupedBlocks();
        $this->assertTrue(is_array($value));
		
        $value = $instance->getNonGroupedBlocks(1);
        $this->assertTrue(is_array($value));
		
        $value = $instance->getNonGroupedBlocks(1, true);
        $this->assertTrue(is_array($value));
		
        $value = $instance->getNonGroupedBlocks(0, false, true);
        $this->assertTrue(is_array($value));
    }
    
    public function test_countSimilarBlocks()
	{
        $instance = new XoopsBlockHandler($this->conn);
        $value = $instance->countSimilarBlocks(1, 1);
        $this->assertEquals(1 ,$value);
		
        $value = $instance->countSimilarBlocks(1, 1, 'shows_func');
        $this->assertEquals(0 ,$value);
    }
    
    public function test_buildContent()
	{
        $instance = new XoopsBlockHandler($this->conn);
        $value = $instance->buildContent(0, 'titi', 'toto');
        $this->assertSame('tototiti',$value);
		
        $value = $instance->buildContent(1, 'titi', 'toto');
        $this->assertSame('tititoto',$value);
		
        $value = $instance->buildContent(2, 'titi', 'toto');
        $this->assertSame('',$value);
    }
    
    public function test_buildTitle()
	{
        $instance = new XoopsBlockHandler($this->conn);
        $title = 'original';
        $value = $instance->buildTitle($title);
        $this->assertEquals($title,$value);
        $title = 'original2';
        $new = 'new';
        $value = $instance->buildTitle($title,$new);
        $this->assertEquals($new,$value);
    }
    
    public function test_getBlockByPerm()
	{
        $instance = new XoopsBlockHandler($this->conn);
        $value = $instance->getBlockByPerm(null);
        $this->assertTrue(empty($value) AND is_array($value));

        $instance = new XoopsBlockHandler($this->conn);
        $value = $instance->getBlockByPerm(1);
        $this->assertTrue(is_array($value));
    }
}
