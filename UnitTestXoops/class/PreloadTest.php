<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class PreloadTest extends MY_UnitTestCase
{
    protected $myClass = 'XoopsPreload';
    
    public function SetUp()
	{
    }
    
    public function test_100()
	{
		$class = $this->myClass;
        $value = $class::getInstance();
        $this->assertInstanceOf('\Xoops\Core\Events', $value);
        $value2 = $class::getInstance();
        $this->assertSame($value2, $value);
    }
	
}
