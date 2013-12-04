<?php
require_once(dirname(__FILE__).'/../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Xoops_RequestTest extends MY_UnitTestCase
{
    protected $myClass = 'Xoops_Request';
    
    public function SetUp()
	{
    }
	
    public function test_getInstance()
	{
		$class = $this->myClass;
		$instance = $class::getInstance();
		$this->assertInstanceOf('Xoops_Request_Http', $instance);
		
		$instance1 = $class::getInstance();
		$this->assertSame($instance1, $instance);
    }

}
