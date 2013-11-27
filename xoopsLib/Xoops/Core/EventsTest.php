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
    
    public function SetUp()
	{
    }

	public function test_100()
	{
		$instance = Xoops\Core\Events::getInstance();
		$this->assertInstanceOf($this->myclass, $instance);
		
		$instance1 = Xoops\Core\Events::getInstance();
		$this->assertSame($instance1, $instance);
	}
	
	public function test_200()
	{
		// triggerEvent
	}
	
	public function test_300()
	{
		// addListener
	}
	
	public function test_400()
	{
		// getEvents
	}

}
