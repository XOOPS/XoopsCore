<?php
require_once(dirname(__FILE__).'/../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Xoops_RequestTest extends MY_UnitTestCase
{
    protected $myclass = 'Xoops_Request';
    
    public function SetUp()
	{
    }
	
    public function test_100()
	{
		$instance = Xoops_Request::getInstance();
		$this->assertInstanceOf('Xoops_Request_Http', $instance);
		
		$instance1 = Xoops_Request::getInstance();
		$this->assertSame($instance1, $instance);
    }

}
