<?php
require_once(dirname(__FILE__).'/../init.php');
 
class PreloadTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsPreload';
    
    public function SetUp() {
    }
    
    public function test_100() {
        $value = XoopsPreload::getInstance();
        $this->assertInstanceOf($this->myclass, $value);
        $value2 = XoopsPreload::getInstance();
        $this->assertSame($value2, $value);
    }
	
}
