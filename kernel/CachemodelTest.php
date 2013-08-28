<?php
require_once(dirname(__FILE__).'/../init.php');

class CachemodelTest extends MY_UnitTestCase
{
    var $myclass='XoopsCachemodelObject';
    
    public function SetUp() {
    }
    
    public function test_100() {
        $instance=new $this->myclass();
        $this->assertInstanceOf($this->myclass,$instance);
		$value=$instance->getVars();
        $this->assertTrue(isset($value['cache_key']));
        $this->assertTrue(isset($value['cache_data']));
        $this->assertTrue(isset($value['cache_expires']));
    }
    
}
