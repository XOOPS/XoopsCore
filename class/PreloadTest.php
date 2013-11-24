<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class PreloadTest extends MY_UnitTestCase
{
    protected $myclass = 'XoopsPreload';
    
    public function SetUp() {
    }
    
    public function test_100() {
        $value = XoopsPreload::getInstance();
        $this->assertInstanceOf('\Xoops\Core\Events', $value);
        $value2 = XoopsPreload::getInstance();
        $this->assertSame($value2, $value);
    }
	
}
