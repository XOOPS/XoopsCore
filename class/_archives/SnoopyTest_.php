<?php
require_once('../init.php');
//require_once(XOOPS_ROOT_PATH.'/class/snoopy.php');
 
class TestOfSnoopy extends UnitTestCase
{
    
    public function SetUp() {
    }
    
    public function test_100() {
        $this->assertEqual(true, false);
    }
}
