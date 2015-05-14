<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class TplfileHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass='XoopsTplfileHandler';
	protected $conn = null;

    public function setUp()
	{
		$this->conn = Xoops::getInstance()->db();
    }

    public function test___construct()
	{
        $instance = new $this->myclass($this->conn);
        $this->assertInstanceOf($this->myclass,$instance);
		$this->assertRegExp('/^.*tplfile$/',$instance->table);
		$this->assertSame('XoopsTplfile',$instance->className);
		$this->assertSame('tpl_id',$instance->keyName);
		$this->assertSame('tpl_refid',$instance->identifierName);
    }

    public function test_getById()
	{
        $instance = new $this->myclass($this->conn);
		$id = 1;
        $value = $instance->getById($id);
        $this->assertInstanceOf('XoopsTplfile',$value);
		
        $value = $instance->getById($id, true);
        $this->assertInstanceOf('XoopsTplfile',$value);
    }

    public function test_loadSource()
	{
        $instance = new $this->myclass($this->conn);
		$source = new XoopsTplfile();
        $value = $instance->loadSource($source);
        $this->assertSame(true, $value);
		
		$source->setVar('tpl_id',1);
        $value = $instance->loadSource($source);
        $this->assertSame(true, $value);
		$tmp = $source->tpl_source();
		$this->assertTrue(!empty($tmp));
    }

    public function test_insertTpl()
	{
        $instance = new $this->myclass($this->conn);
		$source = new XoopsTplfile();
        $value = $instance->insertTpl($source);
        $this->assertSame(true,$value);
    }

    public function test_forceUpdate()
	{
        $instance = new $this->myclass($this->conn);
		$source = new XoopsTplfile();
        $value = $instance->forceUpdate($source);
        $this->assertSame(true,$value);
    }

    public function test_deleteTpl()
	{
        $instance = new $this->myclass($this->conn);
		$source = new XoopsTplfile();
        $source->setDirty();
        $source->setNew();
        $source->setVar('tpl_desc', 'TPL_DESC_DUMMY_TEST');
        $value = $instance->insertTpl($source);
        $this->assertSame(true,$value);

        $value = $instance->deleteTpl($source);
        $this->assertSame(true,$value);
    }

    public function test_getTplObjects()
	{
        $instance = new $this->myclass($this->conn);
        $value = $instance->getTplObjects();
        $this->assertTrue(is_array($value));
		
        $value = $instance->getTplObjects(null, true);
        $this->assertTrue(is_array($value));
		
        $value = $instance->getTplObjects(null, false, true);
        $this->assertTrue(is_array($value));
		
		$criteria = new Criteria('tpl_type', 'dummy');
        $value = $instance->getTplObjects($criteria);
        $this->assertTrue(is_array($value));
        $this->assertTrue(count($value) > 0);
    }

    public function test_getModuleTplCount()
	{
        $instance = new $this->myclass($this->conn);
        $value = $instance->getModuleTplCount('toto');
        $this->assertTrue(empty($value));
		
        $value = $instance->getModuleTplCount('default');
        $this->assertTrue(is_array($value));
        $this->assertTrue(count($value) > 0);
    }

    public function test_find()
	{
        $instance = new $this->myclass($this->conn);
        $value = $instance->find();
        $this->assertTrue(is_array($value));
		
        $value = $instance->find('tpl_set');
        $this->assertTrue(is_array($value));
		
        $value = $instance->find(null, null, null, 'module');
        $this->assertTrue(is_array($value));
		
        $value = $instance->find(null, null, 1);
        $this->assertTrue(is_array($value));
		
        $value = $instance->find(null, null, null, null, 'file');
        $this->assertTrue(is_array($value));
		
        $value = $instance->find(null, 1);
        $this->assertTrue(is_array($value));
		
        $value = $instance->find(null, array(1,2,3));
    }

    public function test_templateExists()
	{
        $instance = new $this->myclass($this->conn);
		
        $value = $instance->templateExists('dummy.html','dummy');
        $this->assertSame(false, $value);
		
        $value = $instance->templateExists('system_block_user.html','default');
        $this->assertSame(true, $value);
    }

}