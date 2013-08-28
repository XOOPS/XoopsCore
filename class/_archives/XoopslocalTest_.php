<?php
require_once('../init.php');
//require_once(XOOPS_ROOT_PATH.'/class/xoopslocal.php');
 
class TestOfXoopslocal extends UnitTestCase
{
    
    public function SetUp() {
    }
    
    public function test_100() {
        $this->assertEqual(true, false);
    }
}
