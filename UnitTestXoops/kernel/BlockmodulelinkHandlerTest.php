<?php
require_once(dirname(__FILE__).'/../init.php');

class BlockmodulelinkHandlerTest extends MY_UnitTestCase
{
    var $myclass='XoopsBlockmodulelinkHandler';
    
    public function SetUp() {
    }
    
    public function test_100() {
        $instance=new $this->myclass();
        $this->assertInstanceOf($this->myclass,$instance);
		$this->assertRegExp('/^.*block_module_link$/',$instance->table);
		$this->assertSame('XoopsBlockmodulelink',$instance->className);
		$this->assertSame('block_id',$instance->keyName);
		$this->assertSame('module_id',$instance->identifierName);
    }
    
}