<?php
require_once(dirname(__FILE__).'/../init.php');

class TplfileHandlerTest extends MY_UnitTestCase
{
    var $myclass='XoopsTplfileHandler';

    public function SetUp()
	{
    }

    public function test_100()
	{
        $instance = new $this->myclass();
        $this->assertInstanceOf($this->myclass,$instance);
		$this->assertRegExp('/^.*tplfile$/',$instance->table);
		$this->assertSame('XoopsTplfile',$instance->className);
		$this->assertSame('tpl_id',$instance->keyName);
		$this->assertSame('tpl_refid',$instance->identifierName);
    }

    public function test_120()
	{
        $instance = new $this->myclass();
		$id = 1;
        $value = $instance->getById($id);
        $this->assertInstanceOf('XoopsTplfile',$value);
		
        $value = $instance->getById($id, true);
        $this->assertInstanceOf('XoopsTplfile',$value);
    }

    public function test_140()
	{
        $instance = new $this->myclass();
		$source = new XoopsTplfile();
        $value = $instance->loadSource($source);
        $this->assertSame(false, $value);
		
		$source->setVar('tpl_id',1);
        $value = $instance->loadSource($source);
        $this->assertSame(true, $value);
		$tmp = $source->tpl_source();
		$this->assertTrue(!empty($tmp));
    }

    public function test_160()
	{
        $instance = new $this->myclass();
		$source = new XoopsTplfile();
        $value = $instance->insertTpl($source);
        $this->assertSame(true,$value);
    }

    public function test_180()
	{
        $instance = new $this->myclass();
		$source = new XoopsTplfile();
        $value = $instance->forceUpdate($source);
        $this->assertSame(true,$value);
    }

    public function test_200()
	{
        $instance = new $this->myclass();
		$source = new XoopsTplfile();
        $value = $instance->deleteTpl($source);
        $this->assertSame(true,$value);
    }

    public function test_220()
	{
        $instance = new $this->myclass();
        $value = $instance->getTplObjects();
        $this->assertTrue(is_array($value));
		
        $value = $instance->getTplObjects(null, true);
        $this->assertTrue(is_array($value));
		
        $value = $instance->getTplObjects(null, false, true);
        $this->assertTrue(is_array($value));
		
		$criteria = new Criteria('tpl_type', 'dummy');
        $value = $instance->getTplObjects($criteria);
        $this->assertTrue(is_array($value) AND empty($value));
    }

    public function test_240()
	{
        $instance = new $this->myclass();
        $value = $instance->getModuleTplCount('toto');
        $this->assertTrue(empty($value));
		
        $value = $instance->getModuleTplCount('default');
        $this->assertTrue(is_array($value) AND count($value) > 0);
    }

    public function test_260()
	{
        $instance = new $this->myclass();
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

    public function test_280()
	{
        $instance = new $this->myclass();
		
        $value = $instance->templateExists('dummy.html','dummy');
        $this->assertSame(false, $value);
		
        $value = $instance->templateExists('system_block_user.html','default');
        $this->assertSame(true, $value);
    }

}