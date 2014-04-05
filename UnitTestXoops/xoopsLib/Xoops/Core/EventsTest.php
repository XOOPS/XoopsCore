<?php
require_once(dirname(__FILE__).'/../../../init_mini.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class EventsTest extends MY_UnitTestCase
{
    protected $myclass = 'Xoops\Core\Events';

	public function test_getInstance()
	{
		$class = $this->myclass;
		$instance = $class::getInstance();
		$this->assertInstanceOf($class, $instance);
		
		$instance1 = $class::getInstance();
		$this->assertSame($instance1, $instance);
	}
	
	public function test_triggerEvent()
	{
        $this->markTestIncomplete('to do');
	}
	
	public function test_addListener()
	{
        $this->markTestIncomplete('to do');
	}
	
	public function test_getEvents()
	{
        $this->markTestIncomplete('to do');
	}

}
