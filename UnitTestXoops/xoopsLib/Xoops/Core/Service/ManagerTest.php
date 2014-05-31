<?php
require_once(dirname(__FILE__).'/../../../../init.php');

use Xoops\Core\Service\Manager;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ManagerTest extends MY_UnitTestCase
{
	protected $myClass = 'Xoops\Core\Service\Manager';
	
	function test___construct()
	{
		$class = $this->myClass;
		$instance = $class::getInstance();
		$this->assertInstanceOf($this->myClass, $instance);
		
		$instance2 = $class::getInstance();
		$this->assertSame($instance, $instance2);
	}
	
	function test_constants()
	{
		$class = $this->myClass;
		$instance = $class::getInstance();
		$this->assertInstanceOf($this->myClass, $instance);
		
		$this->assertTrue(is_int($instance::MODE_EXCLUSIVE));
		$this->assertTrue(is_int($instance::MODE_CHOICE));
		$this->assertTrue(is_int($instance::MODE_PREFERENCE));
		$this->assertTrue(is_int($instance::MODE_MULTIPLE));
	}
	
	function test_saveChoice()
	{
        $this->markTestIncomplete();
	}
	
	function test_registerChoice()
	{
        $this->markTestIncomplete();
	}
	
	function test_listChoices()
	{
        $this->markTestIncomplete();
	}
	
	function test_locate()
	{
        $this->markTestIncomplete();
	}
}
