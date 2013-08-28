<?php
require_once(dirname(__FILE__).'/../init.php');

class TplsetHandlerTest extends MY_UnitTestCase
{
    var $myclass='XoopsTplsetHandler';

    public function SetUp()
	{
    }

    public function test_100()
	{
        $instance = new $this->myclass();
        $this->assertInstanceOf($this->myclass,$instance);
		$this->assertRegExp('/^.*tplset$/',$instance->table);
		$this->assertSame('XoopsTplset',$instance->className);
		$this->assertSame('tplset_id',$instance->keyName);
		$this->assertSame('tplset_name',$instance->identifierName);
    }

    public function test_120()
	{
        $instance = new $this->myclass();
        $value = $instance->getByname(1);
        $this->assertSame(false,$value);
    }

    public function test_140()
	{
        $instance = new $this->myclass();
        $value = $instance->getNameList();
        $this->assertTrue(is_array($value));
    }

}