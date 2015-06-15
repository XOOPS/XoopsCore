<?php
require_once(dirname(__FILE__).'/../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsUserUtilityTest extends \PHPUnit_Framework_TestCase
{
    protected $myClass = 'XoopsUserUtility';
    
    public function test___construct()
	{
		$x = new $this->myClass();
        $this->assertInstanceOf($this->myClass, $x);
    }
     
    public function test_100()
	{
        $this->markTestIncomplete();
    }
}
