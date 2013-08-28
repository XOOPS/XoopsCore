<?php
require_once('../init.php');
//require_once(XOOPS_ROOT_PATH.'/class/class.zipfile.php');
 
class TestOfZipfile extends UnitTestCase
{
    protected $myclass='zipfile';
    
    public function SetUp() {
    }
    
    public function test_100() {
        $this->assertEqual(true, false); //failure by default
    }
}
