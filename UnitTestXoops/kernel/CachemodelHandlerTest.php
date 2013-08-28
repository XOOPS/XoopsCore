<?php
require_once(dirname(__FILE__).'/../init.php');

class CachemodelHandlerTest extends MY_UnitTestCase
{
    var $myclass='XoopsCachemodelHandler';
    
    public function SetUp() {
    }
    
    public function test_100() {
        $instance=new $this->myclass();
        $this->assertInstanceOf($this->myclass,$instance);
		$this->assertRegExp('/^.*cache_model$/',$instance->table);
		$this->assertSame('XoopsCachemodelObject',$instance->className);
		$this->assertSame('cache_key',$instance->keyName);
		$this->assertSame('cache_data',$instance->identifierName);
    }
    
}